<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    /**
     * Default product categories for ALGO NATION (clothing store).
     */
    public const CATEGORIES = [
        'Formal Wear',
        'Bottoms',
        'Outerwear',
        'Footwear',
        'Accessories',
        'Bags',
        'Hats',
        'Casual T-Shirt',
    ];

    /**
     * Total stock units below which a product is considered low on stock.
     */
    public const LOW_STOCK_THRESHOLD = 5;

    /**
     * Status produk yang tersedia.
     */
    public const STATUSES = [
        'active',
        'inactive',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'category',
        'status',
        'image',
        'description',
        'stock',
        'price',
        'weight',
        'length',
        'width',
        'height',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'length' => 'decimal:2',
            'width' => 'decimal:2',
            'height' => 'decimal:2',
        ];
    }

    /**
     * Get the variants for the product.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Generic flash-sale relation (unfiltered).
     *
     * Produk Unggulan dan seluruh halaman HARUS menentukan keaktifan
     * flash sale melalui helper isFlashSaleActive() / accessor final_price,
     * bukan dari relasi ini secara langsung.
     */
    public function flashSale(): HasOne
    {
        return $this->hasOne(FlashSale::class)->latestOfMany();
    }

    /**
     * The single (if any) active flash sale for this product.
     *
     * Aturan aktif (satu-satunya sumber kebenaran, sesuai spesifikasi):
     *   flash_sale.status = active
     *   AND now() berada di antara start_at dan end_at (inklusif).
     *
     * Sengaja TIDAK memfilter stok di sini agar harga selalu mengikuti
     * aturan di atas. Ketersediaan stok ditangani terpisah di cart/checkout.
     */
    public function activeFlashSale(): HasOne
    {
        return $this->hasOne(FlashSale::class)
            ->where('status', 'active')
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now());
    }

    /**
     * Tentukan apakah produk sedang memiliki Flash Sale aktif.
     *
     * Memakai relasi yang sudah di-eager-load bila tersedia agar tidak
     * menambah query (N+1), jika tidak maka query ringan sekali.
     */
    public function isFlashSaleActive(): bool
    {
        $flashSale = null;

        if ($this->relationLoaded('activeFlashSale') && $this->getRelation('activeFlashSale')) {
            return true;
        }

        if ($this->relationLoaded('flashSale')) {
            $flashSale = $this->getRelation('flashSale');
        } else {
            $flashSale = $this->flashSale()->first();
        }

        if (! $flashSale) {
            return false;
        }

        return $flashSale->isActive();
    }

    /**
     * Harga untuk varian tertentu (sumber tunggal untuk cart/checkout).
     *
     * - Flash Sale aktif => harga Flash Sale (mengalahkan semua varian).
     * - Selain itu => harga varian (jika ada) atau harga asli produk.
     */
    public function priceForVariant($variant = null): float
    {
        $flashPrice = $this->flash_sale_price;

        if ($flashPrice !== null) {
            return $flashPrice;
        }

        if ($variant && $variant->price !== null) {
            return (float) $variant->price;
        }

        return (float) $this->price;
    }

    /**
     * The featured-product record (if this product is featured).
     */
    public function featured(): HasOne
    {
        return $this->hasOne(FeaturedProduct::class);
    }

    /**
     * Total sellable stock = base stock + sum of variant stock.
     */
    public function getTotalStockAttribute(): int
    {
        if ($this->relationLoaded('variants')) {
            return (int) $this->stock + (int) $this->getRelation('variants')->sum('stock');
        }

        return $this->stock + $this->variants()->sum('stock');
    }

    /**
     * Whether the product is low on stock.
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->total_stock <= self::LOW_STOCK_THRESHOLD;
    }

    /**
     * Whether the product is out of stock.
     */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->total_stock <= 0;
    }

    /**
     * Display price (base price, or the cheapest variant price if set).
     */
    public function getDisplayPriceAttribute(): float
    {
        if ($this->relationLoaded('variants')) {
            $cheapest = $this->getRelation('variants')
                ->whereNotNull('price')
                ->min('price');

            return (float) ($cheapest ?? $this->price);
        }

        $cheapest = $this->variants()
            ->whereNotNull('price')
            ->orderBy('price')
            ->value('price');

        return (float) ($cheapest ?? $this->price);
    }

    /**
     * The base/regular price the product would show without any promotion.
     * Prefers the cheapest variant price when one is set, otherwise the base price.
     */
    public function getRegularPriceAttribute(): float
    {
        return $this->display_price;
    }

    /**
     * Harga asli produk (sumber: kolom products.price).
     * Tidak pernah diubah oleh Flash Sale maupun Produk Unggulan.
     */
    public function getOriginalPriceAttribute(): float
    {
        return (float) $this->price;
    }

    /**
     * Harga Flash Sale yang sedang aktif, atau null bila tidak ada.
     */
    public function getFlashSalePriceAttribute(): ?float
    {
        if ($this->relationLoaded('activeFlashSale') && $this->getRelation('activeFlashSale')) {
            return (float) $this->getRelation('activeFlashSale')->sale_price;
        }

        if ($this->relationLoaded('flashSale')) {
            $flashSale = $this->getRelation('flashSale');

            return $flashSale && $flashSale->isActive() ? (float) $flashSale->sale_price : null;
        }

        $flashSale = $this->activeFlashSale;

        return $flashSale ? (float) $flashSale->sale_price : null;
    }

    /**
     * Apakah produk sedang memiliki Flash Sale aktif (aturan spesifikasi).
     */
    public function getHasFlashSaleAttribute(): bool
    {
        return $this->flash_sale_price !== null;
    }

    /**
     * Harga akhir produk — SATU-SATUNYA sumber logika harga.
     *
     * Rule:
     * - Flash Sale aktif => sale_price.
     * - Selain itu => display_price (harga termurah varian bila ada,
     *   jika tidak maka products.price).
     *
     * Untuk produk tanpa varian, fallback ini sama persis dengan
     * $product->price sesuai spesifikasi. Harga asli tidak pernah dimutasi.
     */
    public function getFinalPriceAttribute(): float
    {
        return $this->flash_sale_price ?? $this->display_price;
    }

    /**
     * Alias lama agar seluruh halaman lama tetap konsisten.
     * active_price SELALU sama dengan final_price (satu sumber).
     */
    public function getActivePriceAttribute(): float
    {
        return $this->final_price;
    }

    /**
     * Whether the product currently has an active flash sale.
     * Alias agar kode lama tetap jalan; sama dengan has_flash_sale.
     */
    public function getHasActiveFlashSaleAttribute(): bool
    {
        return $this->has_flash_sale;
    }

    /**
     * Estimated shipping weight in grams (defaults to a sensible value when unset).
     */
    public function getWeightGramsAttribute(): float
    {
        return (float) ($this->weight !== null ? $this->weight : 300);
    }

    /**
     * Weight in kilograms, used by the shipping calculation.
     */
    public function getWeightKgAttribute(): float
    {
        return $this->weight_grams / 1000;
    }

    /**
     * Package dimensions in cm, each with a sensible default when unset.
     *
     * @return array{length: float, width: float, height: float}
     */
    public function getDimensionsAttribute(): array
    {
        return [
            'length' => (float) ($this->length ?? 40),
            'width' => (float) ($this->width ?? 30),
            'height' => (float) ($this->height ?? 20),
        ];
    }

    /**
     * Absolute URL for the product image.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        if ($this->image) {
            if (str_starts_with($this->image, 'images/')) {
                return asset($this->image);
            }

            return asset('storage/'.$this->image);
        }

        // Fallback placeholder based on category.
        return 'https://placehold.co/600x400/e9f50b/1b1b18?text='.urlencode($this->category ?? 'Product');
    }

    /**
     * Localized / readable category badge color helper.
     */
    public function getCategoryClassAttribute(): string
    {
        return in_array($this->category, self::CATEGORIES, true)
            ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft'
            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }
}
