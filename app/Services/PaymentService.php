<?php

namespace App\Services;

use App\Models\FlashSale;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Payment gateway (Midtrans) domain logic.
 *
 * All rules around snap token creation, webhook verification and
 * payment-status transitions live here so the controller stays thin
 * and the payment business rules are defined in exactly one place.
 */
class PaymentService
{
    /**
     * Build the item details payload expected by the Midtrans API for a
     * transaction (products plus the shipping line when it applies).
     *
     * @return list<array<string, mixed>>
     */
    public function buildItemDetails(Transaction $transaction): array
    {
        $items = $transaction->details->map(fn ($detail) => [
            'id'       => (string) $detail->product_id,
            'name'     => $detail->product?->name ?? 'Produk',
            'price'    => (int) round($detail->quantity > 0 ? $detail->subtotal / $detail->quantity : 0),
            'quantity' => (int) $detail->quantity,
        ])->all();

        if ($transaction->shipping_cost > 0) {
            $items[] = [
                'id'       => 'SHIPPING',
                'name'     => 'Ongkos Kirim',
                'price'    => (int) $transaction->shipping_cost,
                'quantity' => 1,
            ];
        }

        return $items;
    }

    /**
     * Build the transaction details payload used to create a new snap token.
     *
     * @param  list<array<string, mixed>>  $itemDetails
     * @return array<string, mixed>
     */
    public function buildSnapParams(string $orderId, int $grossAmount, array $itemDetails, array $customerDetails = []): array
    {
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $itemDetails,
        ];

        if (! empty($customerDetails)) {
            $params['customer_details'] = $customerDetails;
        }

        return $params;
    }

    /**
     * Ask Midtrans to mint a fresh snap token for the given parameters.
     *
     * @param  array<string, mixed>  $params
     * @return string|null  The snap token, or null when the request failed.
     */
    public function generateSnapToken(array $params): ?string
    {
        $this->bootstrapConfig();

        try {
            return \Midtrans\Snap::getSnapToken($params);
        } catch (\Throwable $e) {
            report($e);
            Log::error('Midtrans snap token creation failed', [
                'order_id' => $params['transaction_details']['order_id'] ?? null,
                'error'    => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Query the current transaction status directly from the Midtrans API.
     * Used as a fallback/verification when the webhook is unavailable.
     */
    public function checkStatus(string $orderId): ?array
    {
        $this->bootstrapConfig();

        try {
            $response = \Midtrans\Transaction::status($orderId);

            if ($response && isset($response->transaction_status)) {
                return (array) $response;
            }

            return null;
        } catch (\Throwable $e) {
            Log::error('[Midtrans Status Check] API call failed', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Configure the Midtrans SDK from the application config.
     */
    public function bootstrapConfig(): void
    {
        \Midtrans\Config::$serverKey    = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized  = config('midtrans.isSanitized');
        \Midtrans\Config::$is3ds        = config('midtrans.is3ds');
    }

    /**
     * Map Midtrans transaction/fraud status to the internal payment_status.
     */
    public function mapPaymentStatus(?string $transactionStatus, ?string $fraudStatus, string $current): string
    {
        return match (true) {
            $transactionStatus === 'settlement'                           => 'paid',
            $transactionStatus === 'capture' && $fraudStatus === 'accept' => 'paid',
            $transactionStatus === 'pending'                              => 'pending',
            $transactionStatus === 'deny'                                 => 'failed',
            $transactionStatus === 'cancel'                               => 'cancelled',
            $transactionStatus === 'expire'                               => 'expired',
            $transactionStatus === 'failure'                              => 'failed',
            default                                                       => $current,
        };
    }

    /**
     * Map an internal payment_status to the order status.
     */
    public function mapOrderStatus(string $paymentStatus, string $current): string
    {
        return match ($paymentStatus) {
            'paid'      => 'pending',
            'failed',
            'expired',
            'cancelled' => 'cancelled',
            default     => $current,
        };
    }

    /**
     * Apply a payment status transition inside a database transaction.
     * Stock is decremented only on the first transition to paid (idempotent).
     */
    public function applyPaymentStatus(Transaction $transaction, string $newPaymentStatus): void
    {
        $current = $transaction->payment_status ?? 'pending';

        // A transaction that reached a terminal state (expired via the 15-minute
        // deadline, failed, or cancelled) must never be moved away from it — not
        // even backwards to pending or forwards to paid — regardless of any late
        // or duplicate Midtrans notification.
        if (in_array($current, ['expired', 'cancelled', 'failed'])
            && in_array($newPaymentStatus, ['paid', 'pending', 'expired', 'cancelled', 'failed'])) {
            return;
        }

        $wasNotPaid = $current !== 'paid';

        DB::transaction(function () use ($transaction, $newPaymentStatus, $wasNotPaid) {
            $updateData = [
                'payment_status' => $newPaymentStatus,
                'status'         => $this->mapOrderStatus($newPaymentStatus, $transaction->status),
            ];

            if ($newPaymentStatus === 'paid' && $wasNotPaid) {
                $updateData['paid_at'] = now();
            }

            $transaction->update($updateData);

            if ($newPaymentStatus === 'paid' && $wasNotPaid) {
                $this->adjustProductStock($transaction, 1);
            }
        });
    }

    /**
     * Add (positive multiplier) or remove (negative multiplier) stock
     * for every line item in the transaction (variant stock takes precedence).
     */
    public function adjustProductStock(Transaction $transaction, int $multiplier): void
    {
        foreach ($transaction->details as $detail) {
            if ($detail->variant_id) {
                ProductVariant::where('id', $detail->variant_id)
                    ->increment('stock', $detail->quantity * $multiplier);
            } else {
                Product::where('id', $detail->product_id)
                    ->increment('stock', $detail->quantity * $multiplier);
            }
        }
    }

    /**
     * Restore the reserved flash-sale stock for every line item.
     */
    public function restoreFlashSaleStock(Transaction $transaction): void
    {
        foreach ($transaction->details as $detail) {
            $flashSale = FlashSale::where('product_id', $detail->product_id)->first();

            if ($flashSale) {
                $flashSale->increment('stock', $detail->quantity);
            }
        }
    }
}