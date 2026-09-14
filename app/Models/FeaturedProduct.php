<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeaturedProduct extends Model
{
    use HasFactory;

    /**
     * Produk Unggulan HANYA menyimpan referensi product_id.
     * TIDAK ada field harga (featured_price dsb) — harga selalu
     * mengikuti Product ($product->price) dan FlashSale yang aktif
     * melalui $featuredProduct->product->final_price.
     */
    protected $fillable = [
        'product_id',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Delegasi harga ke produk utama agar otomatis sinkron
     * saat harga produk / flash sale berubah (tanpa edit ulang).
     */
    public function getFinalPriceAttribute(): ?float
    {
        return $this->product?->final_price;
    }

    public function getActivePriceAttribute(): ?float
    {
        return $this->product?->active_price;
    }

    public function getOriginalPriceAttribute(): ?float
    {
        return $this->product ? (float) $this->product->price : null;
    }

    public function getRegularPriceAttribute(): ?float
    {
        return $this->product?->regular_price;
    }

    public function getFlashSalePriceAttribute(): ?float
    {
        return $this->product?->flash_sale_price;
    }

    public function getHasFlashSaleAttribute(): bool
    {
        return (bool) $this->product?->has_flash_sale;
    }

    public function getHasActiveFlashSaleAttribute(): bool
    {
        return $this->has_flash_sale;
    }
}
