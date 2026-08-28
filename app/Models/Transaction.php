<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'total_price',
        'shipping_cost',
        'payment_method',
        'shipping_address',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_price'   => 'float',
            'shipping_cost' => 'float',
        ];
    }

    /**
     * Generate a unique invoice number.
     */
    public static function generateInvoiceNumber(): string
    {
        return 'INV/'.date('Ymd').'/'.strtoupper(Str::random(6));
    }

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the details for the transaction.
     */
    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
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
        return date('D, d M Y', strtotime($this->created_at.' +3 days'));
    }

    /**
     * Whether the order can still be cancelled.
     *
     * Cancellation is allowed ONLY when the estimated delivery is
     * still more than 1 day away — i.e. within the first 2 days
     * after the order was placed (created_at + 2 days).
     */
    public function getCanBeCancelledAttribute(): bool
    {
        // Only pending / processing orders can be cancelled.
        if (! in_array($this->status, ['pending', 'processing'])) {
            return false;
        }

        // Cancellation window closes at created_at + 2 days
        // (which is 1 day before the estimated arrival at +3 days).
        return strtotime($this->created_at.' +2 days') > time();
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
     * Human friendly payment status badge classes.
     */
    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
            'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
            'cancelled' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
            default => 'bg-[#e8dcc8] text-[#5c3d25] dark:bg-[#6b4423]/30 dark:text-[#d4a574]',
        };
    }
}

