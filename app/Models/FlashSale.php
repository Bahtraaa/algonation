<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashSale extends Model
{
    protected $fillable = [
        'product_id',
        'normal_price',
        'sale_price',
        'discount_percentage',
        'stock',
        'start_at',
        'end_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'normal_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'stock' => 'integer',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        $now = Carbon::now(config('app.timezone'));

        return $query->where('status', 'active')
            ->where('start_at', '<=', $now)
            ->where('end_at', '>', $now)
            ->where('stock', '>', 0);
    }

    public function syncStatus(): self
    {
        if ($this->status === 'inactive') {
            return $this;
        }

        $now = Carbon::now(config('app.timezone'));
        $status = $now->lt($this->start_at) ? 'scheduled' : ($now->lt($this->end_at) ? 'active' : 'expired');

        if ($this->status !== $status) {
            $this->forceFill(['status' => $status])->saveQuietly();
        }

        return $this->refresh();
    }

    public static function syncAllStatuses(): void
    {
        static::where('status', '!=', 'inactive')->get()->each->syncStatus();
    }

    public function getComputedStatusAttribute(): string
    {
        return $this->status === 'inactive' ? 'inactive' : (
            Carbon::now(config('app.timezone'))->lt($this->start_at) ? 'scheduled' : (
                Carbon::now(config('app.timezone'))->lt($this->end_at) ? 'active' : 'expired'
            )
        );
    }
}
