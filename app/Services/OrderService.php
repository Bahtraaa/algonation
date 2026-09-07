<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

/**
 * Order lifecycle domain logic: delegation of the payment deadline and
 * the business rules that decide whether an order can still be cancelled.
 *
 * All cancellation rules live here so the same conditions are not repeated
 * across controllers and views.
 */
class OrderService
{
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {
    }

    /**
     * Cancellation verdict.
     *
     * @return array{allowed: bool, message: ?string, honorsJson: bool}
     */
    public function validateCancellation(Transaction $transaction): array
    {
        if ($transaction->payment_status === 'expired') {
            return $this->verdict(
                'Pesanan dibatalkan karena pembayaran tidak diselesaikan dalam 15 menit.',
                honorsJson: true,
            );
        }

        if (! in_array($transaction->status, ['pending_payment', 'pending', 'processing'])) {
            return $this->verdict('Pesanan tidak dapat dibatalkan pada status ini.');
        }

        if (! in_array($transaction->shipping_status, Transaction::cancellableShippingStatuses(), true)) {
            $message = match ($transaction->shipping_status) {
                'diserahkan_ke_kurir' => 'Pesanan tidak dapat dibatalkan karena paket sudah diserahkan kepada kurir.',
                'dalam_perjalanan'    => 'Paket sedang dalam perjalanan dan tidak dapat dibatalkan.',
                default               => 'Pesanan tidak dapat dibatalkan pada status pengiriman ini.',
            };

            return $this->verdict($message, honorsJson: true);
        }

        if (! $transaction->can_be_cancelled) {
            return $this->verdict('Pesanan tidak dapat dibatalkan karena akan segera tiba (kurang dari 1 hari).');
        }

        return $this->verdict(null);
    }

    /**
     * Perform the cancellation: restore reserved stock and mark the order
     * as cancelled in every status dimension.
     */
    public function cancel(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            if (($transaction->payment_status ?? 'pending') === 'paid') {
                $this->paymentService->adjustProductStock($transaction, 1);
            }

            $this->paymentService->restoreFlashSaleStock($transaction);

            $transaction->update([
                'status'              => 'cancelled',
                'payment_status'      => 'cancelled',
                'shipping_status'     => 'dibatalkan',
                'shipping_updated_at' => now(),
            ]);
        });
    }

    /**
     * Build a structured cancellation verdict.
     */
    private function verdict(?string $message, bool $honorsJson = false): array
    {
        return [
            'allowed'    => $message === null,
            'message'    => $message,
            'honorsJson' => $honorsJson,
        ];
    }
}