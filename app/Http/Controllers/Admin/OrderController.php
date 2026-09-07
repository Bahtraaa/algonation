<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private readonly ShippingService $shippingService,
    ) {
    }
    /**
     * List all orders with filters and search.
     */
    public function index(Request $request): View
    {
        $query = Transaction::with('user', 'details.product');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('midtrans_order_id', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('shipping_status') && $request->input('shipping_status') !== 'all') {
            $query->where('shipping_status', $request->input('shipping_status'));
        }

        if ($request->filled('payment_status') && $request->input('payment_status') !== 'all') {
            $query->where('payment_status', $request->input('payment_status'));
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        // Server-side deadline check so admins see accurate payment statuses.
        $transactions->each(fn (Transaction $transaction) => $transaction->markExpiredIfPastDue());

        $shippingStatuses = Transaction::shippingStatuses();

        return view('admin.orders.index', compact('transactions', 'shippingStatuses'));
    }

    /**
     * Show single order detail.
     */
    public function show(Transaction $transaction): View
    {
        // Server-side deadline check so admins see accurate payment statuses.
        $transaction->markExpiredIfPastDue();

        $transaction->load('user', 'details.product', 'details.variant');

        $shippingStatuses = Transaction::shippingStatuses();

        return view('admin.orders.show', compact('transaction', 'shippingStatuses'));
    }

    /**
     * Update shipping info (courier, tracking, dates, status).
     */
    public function updateShipping(Request $request, Transaction $transaction): JsonResponse
    {
        $data = $request->validate([
            'shipping_courier'          => ['nullable', 'string', 'max:100'],
            'tracking_number'           => ['nullable', 'string', 'max:100'],
            'shipping_status'           => ['required', 'string', 'in:' . implode(',', array_keys(Transaction::shippingStatuses()))],
            'estimated_delivery_start'  => ['nullable', 'date'],
            'estimated_delivery_end'    => ['nullable', 'date', 'after_or_equal:estimated_delivery_start'],
            'shipped_at'                => ['nullable', 'date'],
        ]);

        $error = $this->shippingService->validateTransition($transaction, $data['shipping_status']);

        if ($error) {
            return response()->json([
                'errors' => ['shipping_status' => [$error]],
            ], 422);
        }

        $updateData = $this->shippingService->resolveUpdate($transaction, $data);

        $transaction->update($updateData);

        $transaction->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Informasi pengiriman berhasil diperbarui.',
            'transaction' => [
                'shipping_status'          => $transaction->shipping_status,
                'shipping_status_label'    => $transaction->shipping_status_label,
                'shipping_courier'         => $transaction->shipping_courier,
                'tracking_number'          => $transaction->tracking_number,
                'estimated_delivery'       => $transaction->estimated_delivery,
                'shipped_at'               => $transaction->shipped_at?->format('d M Y'),
                'delivered_at'             => $transaction->delivered_at?->format('d M Y'),
            ],
        ]);
    }
}
