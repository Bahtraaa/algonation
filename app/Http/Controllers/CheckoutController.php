<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page with the current cart.
     */
    public function show(Request $request): View
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return view('cart.empty');
        }

        return view('checkout.index', compact('cart'));
    }

    /**
     * Create a COD order.
     */
    public function store(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Keranjang Anda kosong.');
        }

        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $data = $request->validate([
            'full_name'    => ['required', 'string', 'max:255'],
            'phone'        => ['required', 'string', 'max:20'],
            'address'      => ['required', 'string', 'max:500'],
            'city'         => ['required', 'string', 'max:120'],
            'postal_code'  => ['nullable', 'string', 'max:10'],
            'notes'        => ['nullable', 'string', 'max:500'],
        ]);

        $subtotal = array_sum(array_map(
            fn ($item) => $item['price'] * $item['quantity'],
            $cart
        ));

        $shipping = CartController::shippingCost($subtotal);

        try {
            $transaction = DB::transaction(function () use ($request, $cart, $data, $subtotal, $shipping, $user) {
                $transaction = Transaction::create([
                    'user_id'          => $user->id,
                    'total_price'      => $subtotal,
                    'shipping_cost'    => $shipping,
                    'payment_method'   => 'COD',
                    'shipping_address' => trim(
                        $data['full_name']."\n".
                        $data['phone']."\n".
                        $data['address'].', '.$data['city'].' '.($data['postal_code'] ?? '')."\n".
                        ($data['notes'] ?? '')
                    ),
                    'status' => 'pending',
                ]);

                foreach ($cart as $item) {
                    $price = $item['price'];
                    $qty   = $item['quantity'];

                    // Decrement product base stock (if item has no variant).
                    if (! $item['variant_id']) {
                        $product = \App\Models\Product::findOrFail($item['product_id']);
                        $product->decrement('stock', $qty);
                    } else {
                        $variant = \App\Models\ProductVariant::findOrFail($item['variant_id']);
                        $variant->decrement('stock', $qty);
                    }

                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id'     => $item['product_id'],
                        'variant_id'     => $item['variant_id'] ?? null,
                        'quantity'       => $qty,
                        'subtotal'       => $price * $qty,
                    ]);
                }

                return $transaction;
            });

            $request->session()->forget('cart');

            return redirect()->route('checkout.receipt', $transaction->id)
                ->with('success', 'Pesanan berhasil dibuat! Terima kasih.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Gagal memproses pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Show the printable receipt after order completion.
     */
    public function receipt(Transaction $transaction): View
    {
        $user = Auth::user();

        abort_unless($user && ($transaction->user_id === $user->id || $user->isAdmin()), 403);

        $transaction->load('details.product', 'details.variant', 'user');

        return view('checkout.receipt', compact('transaction'));
    }

    /**
     * Order history for the logged-in customer.
     */
    public function orders(): View
    {
        $user = Auth::user();

        abort_unless($user, 403);

        $transactions = $user
            ->transactions()
            ->with('details.product')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('transactions'));
    }

    /**
     * Cancel an order (restores product/variant stock).
     */
    public function cancel(Request $request, Transaction $transaction): RedirectResponse
    {
        $user = Auth::user();

        // Authorization: only the order owner or an admin can cancel.
        abort_unless($user && ($transaction->user_id === $user->id || $user->isAdmin()), 403);

        // Guard: only pending / processing orders can be cancelled.
        if (! in_array($transaction->status, ['pending', 'processing'])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan pada status ini.');
        }

        // Guard: cancellation window — order cannot be cancelled when
        // the estimated arrival is less than 1 day away.
        if (! $transaction->can_be_cancelled) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena akan segera tiba (kurang dari 1 hari).');
        }

        DB::transaction(function () use ($transaction) {
            // Restore stock for each detail line.
            foreach ($transaction->details as $detail) {
                if ($detail->variant_id) {
                    \App\Models\ProductVariant::where('id', $detail->variant_id)
                        ->increment('stock', $detail->quantity);
                } else {
                    \App\Models\Product::where('id', $detail->product_id)
                        ->increment('stock', $detail->quantity);
                }
            }

            $transaction->update([
                'status' => 'cancelled',
            ]);
        });

        return back()->with('success', 'Pesanan '.$transaction->invoice_number.' berhasil dibatalkan.');
    }
}
