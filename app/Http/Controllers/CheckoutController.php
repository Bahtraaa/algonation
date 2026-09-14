<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\FlashSale;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly OrderService $orderService,
        private readonly CartService $cartService,
        private readonly ShippingService $shippingService,
    ) {}

    /**
     * Show the checkout page with the current cart.
     * Alamat default otomatis dipilih — user tidak perlu mengetik ulang.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        $user = Auth::user();

        if (! $user instanceof User) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk checkout.');
        }

        $subtotal = $this->cartService->subtotal($cart);
        $shippingItems = $this->shippingItems($cart);

        $addresses = $user->addresses()->get();
        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        // Dukung ?address_id= (mis. setelah tambah alamat dari checkout).
        $selectedAddress = $defaultAddress;
        if ($request->filled('address_id')) {
            $requested = $addresses->firstWhere('id', (int) $request->query('address_id'));
            if ($requested) {
                $selectedAddress = $requested;
            }
        }

        return view('checkout.index', compact('cart', 'subtotal', 'shippingItems', 'addresses', 'defaultAddress', 'selectedAddress'));
    }

    /**
     * Server-side shipping estimate untuk alamat yang DIPILIH user.
     * Frontend hanya DISPLAY; ongkir final dihitung ulang di store().
     * Mendukung address_id (utama) dan fallback country/city/state (legacy).
     */
    public function estimateShipping(Request $request): JsonResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Keranjang Anda kosong.'], 422);
        }

        // Jalur utama: hitung ongkir dari alamat tersimpan milik user.
        if ($request->filled('address_id')) {
            $user = Auth::user();

            if (! $user instanceof User) {
                return response()->json(['error' => 'Silakan login terlebih dahulu.'], 403);
            }

            $validated = $request->validate([
                'address_id' => [
                    'required', 'integer',
                    Rule::exists('addresses', 'id')->where('user_id', $user->id),
                ],
            ]);

            $address = Address::where('id', $validated['address_id'])
                ->where('user_id', $user->id)
                ->firstOrFail();

            $data = $this->dataFromAddress($address);
        } else {
            $data = $request->validate([
                'country' => ['required', 'string', 'max:120'],
                'city' => ['required', 'string', 'max:120'],
                'state' => ['nullable', 'string', 'max:120'],
            ]);
        }

        $items = $this->shippingItems($cart);
        $destination = $this->destination($data);

        $estimate = $this->shippingService->calculateShipping($items, $destination);

        $rateUnavailable = $estimate['shipping_type'] === 'domestic'
            ? $estimate['shipping_zone'] === null
            : $estimate['region'] === null;

        if ($rateUnavailable) {
            return response()->json([
                'error' => 'Sedang tidak dapat pengiriman ke alamat tersebut. Mohon maaf atas tidak kenyamanannya. Silakan hubungi admin untuk bantuan.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'shipping_type' => $estimate['shipping_type'],
            'origin_country' => $estimate['origin_country'],
            'destination_country' => $estimate['destination_country'],
            'destination_city' => $estimate['destination_city'],
            'distance' => $estimate['distance'],
            'distance_estimated' => $estimate['distance_estimated'] ?? false,
            'actual_weight' => $estimate['actual_weight'],
            'volumetric_weight' => $estimate['volumetric_weight'],
            'billable_weight' => $estimate['billable_weight'],
            'shipping_zone' => $estimate['shipping_zone'],
            'region' => $estimate['region'],
            'shipping_cost' => $estimate['shipping_cost'],
            'shipping_courier' => $estimate['shipping_courier'],
            'subtotal' => $this->cartService->subtotal($cart),
            'total' => $this->cartService->subtotal($cart) + $estimate['shipping_cost'],
        ]);
    }

    /**
     * Create a Midtrans Snap order and return the snap token as JSON.
     *
     * Alamat diambil dari address_id milik user (bukan ketikan manual).
     * The ongkir is RECOMPUTED here on the server (source of truth) using the
     * ShippingService — the client never supplies distance, weight, zone, rate
     * or cost. A shipping snapshot is stored on the order so later changes in
     * rates never affect already-placed orders.
     */
    public function store(Request $request): JsonResponse
    {
        FlashSale::syncAllStatuses();

        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Keranjang Anda kosong.'], 422);
        }

        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        // User wajib punya alamat tersimpan.
        if ($user->addresses()->count() === 0) {
            return response()->json(['error' => 'Silakan tambahkan alamat pengiriman terlebih dahulu.'], 422);
        }

        // address_id harus benar-benar milik user yang sedang login.
        // Jangan pernah percaya user_id dari request — ambil dari auth().
        $validated = $request->validate([
            'address_id' => [
                'required', 'integer',
                Rule::exists('addresses', 'id')->where('user_id', $user->id),
            ],
        ], [
            'address_id.required' => 'Silakan pilih alamat pengiriman terlebih dahulu.',
            'address_id.exists' => 'Alamat yang dipilih tidak valid.',
        ]);

        $address = Address::where('id', $validated['address_id'])
            ->where('user_id', $user->id)
            ->first();

        if (! $address) {
            return response()->json(['error' => 'Alamat yang dipilih tidak valid.'], 422);
        }

        $data = $this->dataFromAddress($address);

        $items = $this->shippingItems($cart);
        $destination = $this->destination($data);

        $shipping = $this->shippingService->calculateShipping($items, $destination);

        // A destination without any matching zone (domestic) or region/country
        // (international) cannot produce a real ongkir — refuse the order.
        $rateUnavailable = $shipping['shipping_type'] === 'domestic'
            ? $shipping['shipping_zone'] === null
            : $shipping['region'] === null;

        if ($rateUnavailable) {
            return response()->json([
                'error' => 'Ongkos kirim tidak dapat dihitung untuk tujuan tersebut. Periksa kembali alamat atau hubungi admin.',
            ], 422);
        }

        // Backend is the source of truth for item prices: recompute each line
        // from the current product/variant/flash-sale state (flash sale price
        // wins whenever active, and reverts automatically when it ends).
        $subtotal = 0.0;
        foreach ($items as $item) {
            $subtotal += (float) $item['price'] * (int) $item['quantity'];
        }
        $subtotal = round($subtotal, 2);
        $shippingCost = (float) $shipping['shipping_cost'];
        $grossAmount = (int) round($subtotal + $shippingCost);

        $orderId = 'ORDER-'.$user->id.'-'.time();

        $itemDetails = [];
        foreach ($items as $item) {
            $itemDetails[] = [
                'id' => (string) $item['product_id'],
                'name' => $item['name'].($item['variant'] ? ' ('.$item['variant'].')' : ''),
                'price' => (int) round($item['price']),
                'quantity' => (int) $item['quantity'],
            ];
        }
        if ($shippingCost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'name' => 'Ongkos Kirim',
                'price' => (int) round($shippingCost),
                'quantity' => 1,
            ];
        }

        $params = $this->paymentService->buildSnapParams($orderId, $grossAmount, $itemDetails, [
            'first_name' => $data['full_name'],
            'phone' => $data['phone'],
            'email' => $user->email,
            'address' => $data['address'].', '.$data['city'].' '.($data['postal_code'] ?? ''),
        ]);

        $snapToken = $this->paymentService->generateSnapToken($params);

        if (! $snapToken) {
            return response()->json(['error' => 'Gagal membuat sesi pembayaran. Silakan coba lagi.'], 500);
        }

        $transaction = DB::transaction(function () use (

            $items,
            $data,
            $address,
            $subtotal,
            $shipping,
            $shippingCost,
            $user,
            $orderId,
            $snapToken
        ) {
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'total_price' => $subtotal,
                'shipping_cost' => $shippingCost,
                'payment_method' => 'midtrans',
                'shipping_address' => trim(
                    $data['full_name']."\n".
                    $data['phone']."\n".
                    $data['address'].', '.$data['district'].', '.($data['state'] ? $data['state'].', ' : '').$data['city'].' '.($data['postal_code'] ?? '')."\n".
                    $data['country']."\n".
                    ($data['notes'] ?? '')
                ),
                // Snapshot alamat — tetap tersimpan walau user mengedit alamat akun.
                'shipping_name' => $address->recipient_name,
                'shipping_phone' => $address->phone,
                'shipping_country' => $address->country,
                'shipping_province' => $address->province,
                'shipping_city' => $address->city,
                'shipping_district' => $address->district,
                'shipping_postal_code' => $address->postal_code,
                'shipping_note' => $address->note,
                'shipping_label' => $address->label,
                'status' => 'pending_payment',
                'payment_status' => 'pending',
                'payment_due_at' => now()->addMinutes(Transaction::PAYMENT_DURATION_MINUTES),
                'shipping_status' => 'menunggu_diproses',
                'midtrans_order_id' => $orderId,
                'midtrans_snap_token' => $snapToken,
                // Shipping snapshot (immutable after this order is recorded).
                'shipping_type' => $shipping['shipping_type'],
                'origin_country' => $shipping['origin_country'],
                'destination_country' => $shipping['destination_country'],
                'destination_city' => $shipping['destination_city'],
                'destination_state' => $shipping['destination_state'],
                'destination_postal_code' => $shipping['destination_postal_code'],
                'shipping_distance' => $shipping['distance'],
                'actual_weight' => $shipping['actual_weight'],
                'volumetric_weight' => $shipping['volumetric_weight'],
                'billable_weight' => $shipping['billable_weight'],
                'shipping_zone' => $shipping['shipping_zone'],
                'shipping_courier' => $shipping['shipping_courier'],
            ]);

            foreach ($items as $item) {
                $flashSale = ! empty($item['flash_sale'])
                    ? $item['flash_sale']
                    : null;

                if (! empty($item['flash_sale_id']) && ! $flashSale) {
                    throw new \RuntimeException('Flash sale sudah berakhir atau tidak aktif.');
                }

                if ($flashSale && $flashSale->stock < $item['quantity']) {
                    throw new \RuntimeException('Stok flash sale tidak mencukupi.');
                }

                $flashSale?->decrement('stock', $item['quantity']);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'subtotal' => (float) $item['price'] * (int) $item['quantity'],
                ]);
            }

            return $transaction;
        });

        Log::info('Midtrans order created', [
            'transaction_id' => $transaction->id,
            'midtrans_order_id' => $orderId,
            'user_id' => $user->id,
            'address_id' => $address->id,
            'gross_amount' => $grossAmount,
            'shipping_cost' => $shippingCost,
        ]);

        $request->session()->forget('cart');

        return response()->json([
            'snap_token' => $snapToken,
            'redirect_url' => route('checkout.receipt', $transaction->id),
            'finalize_url' => route('orders.finalize', $transaction->id),
        ]);
    }

    /**
     * Build the authoritative shipping items for the current cart, loading
     * live product/variant/flash-sale data from the database so prices and
     * weights always reflect the current state.
     *
     * @param  array<string, array<string, mixed>>  $cart
     * @return list<array<string, mixed>>
     */
    private function shippingItems(array $cart): array
    {
        $productIds = array_values(array_unique(array_column($cart, 'product_id')));

        $products = Product::with('variants', 'flashSale', 'activeFlashSale')
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $items = [];

        foreach ($cart as $item) {
            $product = $products->get($item['product_id']);

            if (! $product) {
                continue;
            }

            $variantId = $item['variant_id'] ?? null;
            $variant = $variantId ? $product->variants->firstWhere('id', $variantId) : null;

            // Server-authoritative pricing: satu sumber via priceForVariant().
            // Flash Sale aktif (status=active + now between start/end) menang.
            $candidate = $product->relationLoaded('activeFlashSale')
                ? $product->getRelation('activeFlashSale')
                : $product->activeFlashSale;
            $candidate = $candidate ?? ($product->relationLoaded('flashSale') ? $product->getRelation('flashSale') : null);
            $flashSale = $candidate && $candidate->isActive() ? $candidate : null;
            $useFlashPrice = $flashSale && $flashSale->stock > 0;
            $price = $useFlashPrice ? (float) $flashSale->sale_price : $product->priceForVariant($variant);
            if (! $useFlashPrice) {
                $flashSale = null;
            }

            $items[] = [
                'product_id' => $product->id,
                'variant_id' => $variantId,
                'name' => $product->name,
                'variant' => $variant?->display_name ?? $variant?->name,
                'quantity' => (int) ($item['quantity'] ?? 1),
                'price' => $price,
                'image_url' => $variant?->image_url ?? $product->image_url,
                'flash_sale' => $flashSale,
                'flash_sale_id' => $flashSale?->id,
                'weight_kg' => $product->weight_kg,
                'dimensions' => $product->dimensions,
            ];
        }

        return $items;
    }

    /**
     * Petakan Address tersimpan ke format data checkout lama
     * (agar perhitungan ongkir & Midtrans tidak berubah).
     */
    private function dataFromAddress(Address $address): array
    {
        return [
            'full_name' => $address->recipient_name,
            'phone' => $address->phone,
            'address' => $address->address,
            'country' => $address->country,
            'state' => $address->province,
            'city' => $address->city,
            'district' => $address->district,
            'postal_code' => $address->postal_code,
            'notes' => $address->note,
        ];
    }

    /**
     * Build the destination payload for the shipping calculation.
     *
     * No HTTP here on purpose: ShippingService::shippingDistance() resolves
     * coordinates server-side in ONE place with progressive fallback
     * (district+city+state+country → … → country-only → offline country
     * centre) plus result caching. Pre-geocoding here used to double the
     * Nominatim calls per estimate and poisoned international queries with
     * the domestic postal code (e.g. "Austin … 40123" → zero results → 0 km).
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function destination(array $data): array
    {
        return [
            'country' => (string) ($data['country'] ?? 'Indonesia'),
            'state' => (string) ($data['state'] ?? ''),
            'city' => (string) ($data['city'] ?? ''),
            'district' => (string) ($data['district'] ?? ''),
            'postal_code' => $data['postal_code'] ?? null,
            'latitude' => null,
            'longitude' => null,
        ];
    }

    /**
     * Handle Midtrans notification (webhook).
     *
     * CRITICAL: We parse the raw body manually instead of using
     * \Midtrans\Notification() because Laravel may have already
     * consumed php://input. The Notification class tries to read
     * php://input internally and fails when it's empty.
     */
    public function notification(Request $request): Response
    {
        try {
            $rawBody = $request->getContent();
            $body = json_decode($rawBody, true);

            if (empty($body)) {
                Log::error('[Midtrans Webhook] Empty or invalid JSON body', ['raw' => $rawBody]);

                return response('Invalid request body', 400);
            }

            $orderId = $body['order_id'] ?? null;
            $statusCode = $body['status_code'] ?? null;
            $grossAmount = $body['gross_amount'] ?? null;
            $signatureKey = $body['signature_key'] ?? null;
            $transactionStatus = $body['transaction_status'] ?? null;
            $fraudStatus = $body['fraud_status'] ?? null;

            if (! $orderId || ! $transactionStatus) {
                Log::error('[Midtrans Webhook] Missing required fields', compact('orderId', 'transactionStatus'));

                return response('Missing required fields', 400);
            }

            // Signature validation against the server key.
            $serverKey = config('midtrans.serverKey');
            $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

            if ($signatureKey === null || ! hash_equals($expectedSignature, $signatureKey)) {
                Log::warning('[Midtrans Webhook] Signature mismatch - rejected', ['order_id' => $orderId]);

                return response('Invalid signature', 403);
            }

            $transaction = Transaction::where('midtrans_order_id', $orderId)->first();

            if (! $transaction) {
                Log::error('[Midtrans Webhook] Order not found', ['midtrans_order_id' => $orderId]);

                return response('Order not found', 404);
            }

            // Verify against the Midtrans API FIRST (double-check) so a confirmed
            // settlement that arrived late can never be overwritten by the local
            // 15-minute deadline. The deadline is only a fallback for orders that
            // are genuinely still unpaid upstream.
            $apiStatus = $this->paymentService->checkStatus($orderId);

            if ($apiStatus !== null) {
                $transactionStatus = $apiStatus['transaction_status'] ?? $transactionStatus;
                $fraudStatus = $apiStatus['fraud_status'] ?? $fraudStatus;
            }

            $newPaymentStatus = $this->paymentService->mapPaymentStatus(
                $transactionStatus,
                $fraudStatus,
                $transaction->payment_status ?? 'pending'
            );

            // Nothing has been confirmed upstream (still pending): only then do we
            // enforce the local payment deadline.
            if ($newPaymentStatus === 'pending') {
                $transaction->refresh();
                $transaction->markExpiredIfPastDue();
                $transaction->refresh();
                $newPaymentStatus = $transaction->payment_status;
            }

            // Skip a no-op update so repeated webhooks don't touch the database.
            if ($newPaymentStatus === ($transaction->payment_status ?? 'pending')) {
                return response('OK', 200);
            }

            $this->paymentService->applyPaymentStatus($transaction, $newPaymentStatus);

            $transaction->refresh();

            Log::info('[Midtrans Webhook] Payment status updated', [
                'transaction_id' => $transaction->id,
                'payment_status' => $transaction->payment_status,
                'status' => $transaction->status,
                'paid_at' => $transaction->paid_at?->toISOString(),
            ]);

            return response('OK', 200);
        } catch (\Throwable $e) {
            Log::error('[Midtrans Webhook] Unexpected error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response('Internal error', 500);
        }
    }

    /**
     * Manually check and sync payment status from Midtrans API.
     * Called by frontend after payment popup closes as a fallback.
     * Also useful when webhook is delayed or not received.
     */
    public function checkStatus(Request $request, Transaction $transaction): JsonResponse
    {
        $this->authorizeOwner($transaction);

        // If already paid, just return current status.
        if (($transaction->payment_status ?? 'pending') === 'paid') {
            return response()->json([
                'payment_status' => $transaction->payment_status,
                'paid_at' => $transaction->paid_at?->toISOString(),
                'source' => 'database',
            ]);
        }

        // Query the Midtrans API FIRST so a confirmed payment always wins over
        // the local 15-minute deadline (fixes "user paid but still shows unpaid").
        $apiStatus = $this->paymentService->checkStatus($transaction->midtrans_order_id);

        if ($apiStatus === null) {
            // Midtrans unreachable: fall back to the server-side deadline only.
            $transaction->refresh();
            $transaction->markExpiredIfPastDue();

            return response()->json([
                'payment_status' => $transaction->payment_status,
                'paid_at' => $transaction->paid_at?->toISOString(),
                'source' => 'database',
            ]);
        }

        $newPaymentStatus = $this->paymentService->mapPaymentStatus(
            $apiStatus['transaction_status'] ?? null,
            $apiStatus['fraud_status'] ?? null,
            $transaction->payment_status ?? 'pending'
        );

        // Still genuinely unpaid upstream: enforce the local deadline.
        if ($newPaymentStatus === 'pending') {
            $transaction->refresh();
            $transaction->markExpiredIfPastDue();
            $transaction->refresh();

            return response()->json([
                'payment_status' => $transaction->payment_status,
                'paid_at' => $transaction->paid_at?->toISOString(),
                'source' => 'database',
            ]);
        }

        if ($newPaymentStatus !== ($transaction->payment_status ?? 'pending')) {
            $this->paymentService->applyPaymentStatus($transaction, $newPaymentStatus);
            $transaction->refresh();
        }

        Log::info('[Midtrans Status Check] Synced via API', [
            'transaction_id' => $transaction->id,
            'midtrans_order_id' => $transaction->midtrans_order_id,
            'payment_status' => $transaction->payment_status,
            'paid_at' => $transaction->paid_at?->toISOString(),
        ]);

        return response()->json([
            'payment_status' => $transaction->payment_status,
            'paid_at' => $transaction->paid_at?->toISOString(),
            'source' => 'midtrans_api',
        ]);
    }

    /**
     * Finalize (verify + apply) a payment right after the Snap popup reports
     * onSuccess. The Midtrans webhook can be delayed or unreachable (typical of
     * local development), so the client-confirmed result is verified here with
     * the same signature rules as the webhook before the order is marked paid.
     */
    public function finalize(Request $request, Transaction $transaction): JsonResponse
    {
        $this->authorizeOwner($transaction);

        $orderId = $request->input('order_id');

        if (! $orderId || $transaction->midtrans_order_id !== $orderId) {
            return response()->json(['success' => false, 'message' => 'Order tidak cocok.'], 422);
        }

        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');

        // Verify the onSuccess result with the same signature rules as the
        // webhook. The signature is produced by Midtrans with our server key, so
        // a client cannot forge it — and this path needs no network round-trip,
        // so it can never hang when the sandbox/local network is unreachable.
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');
        $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.config('midtrans.serverKey'));
        $signatureValid = $signatureKey !== null && hash_equals($expectedSignature, $signatureKey);

        if (! $signatureValid) {
            // Fall back to the authoritative status API (rare: legacy channels
            // that omit the signature). Reject when the API is unreachable.
            $apiStatus = $this->paymentService->checkStatus($orderId);

            if ($apiStatus === null) {
                Log::warning('[Midtrans Finalize] Signature mismatch and API unreachable - rejected', ['midtrans_order_id' => $orderId]);

                return response()->json(['success' => false, 'message' => 'Verifikasi pembayaran gagal. Silakan tunggu konfirmasi.'], 422);
            }

            $transactionStatus = $apiStatus['transaction_status'] ?? $transactionStatus;
            $fraudStatus = $apiStatus['fraud_status'] ?? $fraudStatus;
        }

        $newPaymentStatus = $this->paymentService->mapPaymentStatus(
            $transactionStatus,
            $fraudStatus,
            $transaction->payment_status ?? 'pending'
        );

        if ($newPaymentStatus === 'paid' && ($transaction->payment_status ?? 'pending') !== 'paid') {
            $this->paymentService->applyPaymentStatus($transaction, 'paid');
            $transaction->refresh();
        }

        Log::info('[Midtrans Finalize] Payment finalized', [
            'transaction_id' => $transaction->id,
            'midtrans_order_id' => $orderId,
            'payment_status' => $transaction->payment_status,
            'paid_at' => $transaction->paid_at?->toISOString(),
        ]);

        return response()->json([
            'success' => $transaction->payment_status === 'paid',
            'payment_status' => $transaction->payment_status,
            'paid_at' => $transaction->paid_at?->toISOString(),
        ]);
    }

    /**
     * Show the printable receipt after order completion.
     */
    public function receipt(Transaction $transaction): View
    {
        $this->authorizeOwner($transaction);

        // Server-side deadline check so the receipt always reflects the true status.
        $transaction->markExpiredIfPastDue();

        $transaction->load('details.product', 'details.variant', 'user');

        return view('checkout.receipt', compact('transaction'));
    }

    /**
     * Order history for the logged-in customer.
     */
    public function orders(): View
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        $transactions = $user
            ->transactions()
            ->with('details.product')
            ->latest()
            ->paginate(10);

        // Server-side deadline check so expired orders are not shown as payable.
        $transactions->each(fn (Transaction $transaction) => $transaction->markExpiredIfPastDue());

        return view('orders.index', compact('transactions'));
    }

    /**
     * Show order detail with shipping tracking for the customer.
     */
    public function orderDetail(Transaction $transaction): View
    {
        $this->authorizeOwner($transaction);

        // Server-side deadline check so the detail page reflects the true status.
        $transaction->markExpiredIfPastDue();

        $transaction->load('details.product', 'details.variant', 'user');

        return view('orders.show', compact('transaction'));
    }

    /**
     * Regenerate a fresh Snap token for a pending Midtrans order.
     */
    public function pay(Request $request, Transaction $transaction): JsonResponse
    {
        $this->authorizeOwner($transaction);

        // Server-side deadline check: expired unpaid orders can no longer be paid.
        $transaction->markExpiredIfPastDue();

        // Authorise payment using the single source of truth. The frontend button
        // is hidden for non-payable orders, but the endpoint must still validate
        // server-side so a manual API call cannot bypass the rules.
        if (! $transaction->isPayable() || $transaction->payment_method !== 'midtrans') {
            $message = match ($transaction->payment_status) {
                'cancelled' => 'Pesanan ini sudah dibatalkan dan tidak dapat dibayar kembali.',
                'expired' => 'Pesanan dibatalkan karena pembayaran tidak diselesaikan dalam 15 menit.',
                'paid' => 'Pesanan ini sudah dibayar.',
                default => 'Pesanan ini tidak dapat dibayar.',
            };

            return response()->json(['success' => false, 'message' => $message], 422);
        }

        $itemDetails = $this->paymentService->buildItemDetails($transaction);

        $params = $this->paymentService->buildSnapParams(
            $transaction->midtrans_order_id,
            (int) $transaction->total_price + (int) $transaction->shipping_cost,
            $itemDetails,
        );

        $snapToken = $this->paymentService->generateSnapToken($params);

        if (! $snapToken) {
            return response()->json(['error' => 'Gagal membuat sesi pembayaran. Silakan coba lagi.'], 500);
        }

        $transaction->update(['midtrans_snap_token' => $snapToken]);

        return response()->json([
            'snap_token' => $snapToken,
            'redirect_url' => route('checkout.receipt', $transaction->id),
            'finalize_url' => route('orders.finalize', $transaction->id),
        ]);
    }

    /**
     * Cancel an order (restores product/variant/flash sale stock).
     */
    public function cancel(Request $request, Transaction $transaction): RedirectResponse|JsonResponse
    {
        $this->authorizeOwner($transaction);

        // Server-side deadline check: an order past the 15-minute deadline is
        // already expired and needs no manual cancellation.
        $transaction->markExpiredIfPastDue();

        $verdict = $this->orderService->validateCancellation($transaction);

        if (! $verdict['allowed']) {
            if ($verdict['honorsJson'] && $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $verdict['message']], 422);
            }

            return back()->with('error', $verdict['message']);
        }

        $this->orderService->cancel($transaction);

        return back()->with('success', 'Pesanan '.$transaction->invoice_number.' berhasil dibatalkan.');
    }

    /**
     * Make sure the authenticated user owns the transaction (or is an admin).
     */
    private function authorizeOwner(Transaction $transaction): void
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        abort_unless($transaction->user_id === $user->id || $user->isAdmin(), 403);
    }
}
