<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Number of minutes a pending unpaid transaction is kept alive.
     */
    public const PAYMENT_DURATION_MINUTES = 15;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'address_id',
        'total_price',
        'shipping_cost',
        'payment_method',
        'shipping_address',
        'shipping_name',
        'shipping_phone',
        'shipping_country',
        'shipping_province',
        'shipping_city',
        'shipping_district',
        'shipping_postal_code',
        'shipping_note',
        'shipping_label',
        'status',
        'payment_status',
        'paid_at',
        'payment_due_at',
        'midtrans_order_id',
        'midtrans_snap_token',
        'shipping_courier',
        'tracking_number',
        'shipping_status',
        'estimated_delivery_start',
        'estimated_delivery_end',
        'shipped_at',
        'delivered_at',
        'shipping_updated_at',
        'shipping_type',
        'origin_country',
        'destination_country',
        'destination_city',
        'destination_state',
        'destination_postal_code',
        'shipping_distance',
        'actual_weight',
        'volumetric_weight',
        'billable_weight',
        'shipping_zone',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_price'             => 'float',
            'shipping_cost'           => 'float',
            'paid_at'                 => 'datetime',
            'payment_due_at'          => 'datetime',
            'estimated_delivery_start' => 'date',
            'estimated_delivery_end'   => 'date',
            'shipped_at'              => 'datetime',
            'delivered_at'            => 'datetime',
            'shipping_updated_at'     => 'datetime',
            'shipping_distance'       => 'decimal:2',
            'actual_weight'           => 'decimal:2',
            'volumetric_weight'       => 'decimal:2',
            'billable_weight'         => 'decimal:2',
        ];
    }

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alamat yang dipilih saat checkout (bisa null jika alamat dihapus —
     * snapshot di bawah tetap menyimpan histori).
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * Get the details for the transaction.
     */
    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Apakah order ini punya snapshot alamat baru (hasil fitur Alamat Saya)?
     * Order lama hanya punya kolom shipping_address teks.
     */
    public function getHasAddressSnapshotAttribute(): bool
    {
        return ! empty($this->shipping_name) || ! empty($this->shipping_phone);
    }

    /**
     * Alamat pengiriman untuk ditampilkan — prioritaskan snapshot baru,
     * fallback ke kolom shipping_address lama.
     */
    public function getShippingDisplayAttribute(): string
    {
        if ($this->has_address_snapshot) {
            $lines = array_filter([
                $this->shipping_name.($this->shipping_phone ? ' ('.$this->shipping_phone.')' : ''),
                $this->shipping_address,
                trim(implode(', ', array_filter([$this->shipping_district, $this->shipping_city]))),
                trim(implode(', ', array_filter([
                    $this->shipping_province,
                    $this->shipping_postal_code,
                ]))),
                $this->shipping_country,
                $this->shipping_note ? 'Catatan: '.$this->shipping_note : null,
            ]);

            return implode("\n", $lines);
        }

        return (string) $this->shipping_address;
    }

    /**
     * The invoice number attribute.
     */
    public function getInvoiceNumberAttribute(): string
    {
        return 'INV/'.date('Ymd', strtotime($this->created_at)).'/'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Estimated delivery date (3 working days after order).
     */
    public function getEstimatedDeliveryAttribute(): string
    {
        if ($this->estimated_delivery_start && $this->estimated_delivery_end) {
            $start = $this->estimated_delivery_start->format('j');
            $end = $this->estimated_delivery_end->format('j');
            $month = $this->estimated_delivery_end->format('F Y');
            return $start . '-' . $end . ' ' . $month;
        }

        return date('D, d M Y', strtotime($this->created_at.' +3 days'));
    }

    /**
     * Whether the order can still be cancelled.
     *
     * Cancellation is allowed ONLY when the estimated delivery is
     * still more than 1 day away, i.e. within the first 2 days
     * after the order was placed (created_at + 2 days).
     */
    public function getCanBeCancelledAttribute(): bool
    {
        if (! in_array($this->status, ['pending_payment', 'pending', 'processing'])) {
            return false;
        }

        if (! in_array($this->shipping_status, self::cancellableShippingStatuses(), true)) {
            return false;
        }

        return strtotime($this->created_at.' +2 days') > time();
    }

    /**
     * Shipping statuses that still allow customer cancellation.
     */
    public static function cancellableShippingStatuses(): array
    {
        return ['menunggu_diproses', 'pesanan_diproses', 'dikemas'];
    }

    /**
     * Human friendly remaining time before the cancellation window closes.
     */
    public function getCancellationDeadlineAttribute(): string
    {
        $remaining = strtotime($this->created_at.' +2 days') - time();

        if ($remaining <= 0) {
            return 'Pesanan akan segera tiba';
        }

        if ($remaining < 3600) {
            return '±'.ceil($remaining / 60).' menit lagi';
        }

        if ($remaining < 86400) {
            return '±'.ceil($remaining / 3600).' jam lagi';
        }

        return '±'.ceil($remaining / 86400).' hari lagi';
    }

    /**
     * Human friendly payment status label.
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status ?? 'pending') {
            'pending'   => 'Menunggu proses pembayaran',
            'paid'      => 'Pembayaran Berhasil',
            'failed'    => 'Pembayaran Gagal',
            'expired'   => 'Pembayaran Kedaluwarsa',
            'cancelled' => 'Pembayaran Dibatalkan',
            default     => 'Menunggu proses pembayaran',
        };
    }

    /**
     * Badge classes for payment_status.
     */
    public function getPaymentStatusClassAttribute(): string
    {
        return match ($this->payment_status ?? 'pending') {
            'pending'   => 'bg-[#e8dcc8] text-[#5c3d25] dark:bg-[#6b4423]/30 dark:text-[#d4a574]',
            'paid'      => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
            'failed'    => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
            'expired'   => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
            'cancelled' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
            default     => 'bg-[#e8dcc8] text-[#5c3d25] dark:bg-[#6b4423]/30 dark:text-[#d4a574]',
        };
    }

    /**
     * Automatically mark an unpaid transaction as expired once its
     * 15-minute payment deadline has passed.
     *
     * This is a server-side safeguard and does NOT rely on any
     * frontend timer: a user cannot bypass it by tampering with JS.
     */
    public function markExpiredIfPastDue(): bool
    {
        if (($this->payment_status ?? 'pending') !== 'pending') {
            return false;
        }

        $due = $this->payment_due_at;

        if (! $due || $due->gt(now())) {
            return false;
        }

        DB::transaction(function () {
            foreach ($this->details as $detail) {
                $flashSale = FlashSale::where('product_id', $detail->product_id)->first();
                if ($flashSale) {
                    $flashSale->increment('stock', $detail->quantity);
                }
            }

            $this->update([
                'status'              => 'cancelled',
                'payment_status'      => 'expired',
                'shipping_status'     => 'dibatalkan',
                'shipping_updated_at' => now(),
            ]);

            $this->refresh();
        });

        Log::info('Midtrans order auto-expired (15 minute deadline)', [
            'transaction_id'    => $this->id,
            'midtrans_order_id' => $this->midtrans_order_id,
            'payment_due_at'    => $this->payment_due_at?->toISOString(),
        ]);

        return true;
    }

    /**
     * Whether this order can still be paid via the payment gateway.
     *
     * An order is payable only when:
     * - it is still pending payment (not paid, not cancelled, not expired, not failed)
     * - its order status is still a payable/open one
     * - the 15-minute payment deadline has NOT passed.
     *
     * This is the single source of truth used both to show/hide the
     * "Bayar Sekarang" button and to authorise the pay endpoint.
     */
    public function isPayable(): bool
    {
        if (($this->payment_status ?? 'pending') !== 'pending') {
            return false;
        }

        if (! in_array($this->status, ['pending_payment', 'pending'])) {
            return false;
        }

        $due = $this->payment_due_at;

        if ($due && $due->lte(now())) {
            return false;
        }

        return true;
    }

    /**
     * All available shipping statuses.
     */
    public static function shippingStatuses(): array
    {
        return [
            'menunggu_diproses'      => 'Menunggu Diproses',
            'pesanan_diproses'       => 'Pesanan Diproses',
            'dikemas'                => 'Dikemas',
            'diserahkan_ke_kurir'    => 'Diserahkan ke Kurir',
            'dalam_perjalanan'       => 'Dalam Perjalanan',
            'tiba_di_kota_tujuan'    => 'Tiba di Kota Tujuan',
            'sedang_diantar'         => 'Sedang Diantar',
            'pesanan_diterima'       => 'Pesanan Diterima',
            'pengiriman_gagal'       => 'Pengiriman Gagal',
            'dibatalkan'             => 'Dibatalkan',
        ];
    }

    /**
     * Human label for the current shipping status.
     */
    public function getShippingStatusLabelAttribute(): string
    {
        return self::shippingStatuses()[$this->shipping_status] ?? 'Menunggu Diproses';
    }

    /**
     * Human friendly payment method label.
     *
     * COD / Cash on Delivery has been removed from the platform; online
     * payment via the payment gateway is the only supported method. Legacy
     * 'COD' values (if any remain in older databases) map to the same label.
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        $labels = [
            'midtrans'         => 'Pembayaran Online',
            'cod'              => 'Pembayaran Online',
            'cash_on_delivery' => 'Pembayaran Online',
        ];

        return $labels[strtolower((string) $this->payment_method)] ?? 'Pembayaran Online';
    }

    /**
     * Badge classes for shipping_status.
     */
    public function getShippingStatusClassAttribute(): string
    {
        return match ($this->shipping_status) {
            'menunggu_diproses'      => 'bg-[#e8dcc8] text-[#5c3d25] dark:bg-[#6b4423]/30 dark:text-[#d4a574]',
            'pesanan_diproses'       => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
            'dikemas'                => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300',
            'diserahkan_ke_kurir'    => 'bg-violet-100 text-violet-800 dark:bg-violet-900/40 dark:text-violet-300',
            'dalam_perjalanan'       => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
            'tiba_di_kota_tujuan'    => 'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-300',
            'sedang_diantar'         => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/40 dark:text-cyan-300',
            'pesanan_diterima'       => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
            'pengiriman_gagal'       => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
            'dibatalkan'             => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
            default                  => 'bg-[#e8dcc8] text-[#5c3d25] dark:bg-[#6b4423]/30 dark:text-[#d4a574]',
        };
    }

    /**
     * Ordered list of tracking steps (for timeline).
     */
    public static function trackingSteps(): array
    {
        return [
            'pesanan_dibuat',
            'pembayaran_berhasil',
            'pesanan_diproses',
            'dikemas',
            'diserahkan_ke_kurir',
            'dalam_perjalanan',
            'sedang_diantar',
            'pesanan_diterima',
        ];
    }

    /**
     * Map shipping_status to the corresponding tracking step index.
     */
    public function getCurrentTrackingIndex(): int
    {
        if ($this->payment_status !== 'paid') {
            return 0;
        }

        $map = [
            'menunggu_diproses'      => 1,
            'pesanan_diproses'       => 2,
            'dikemas'                => 3,
            'diserahkan_ke_kurir'    => 4,
            'dalam_perjalanan'       => 5,
            'tiba_di_kota_tujuan'    => 5,
            'sedang_diantar'         => 6,
            'pesanan_diterima'       => 7,
            'pengiriman_gagal'       => -1,
            'dibatalkan'             => -1,
        ];

        return $map[$this->shipping_status] ?? 1;
    }
}
