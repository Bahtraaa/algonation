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
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'category',
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
     * The single (if any) active flash sale for this product.
     */
    public function activeFlashSale(): HasOne
    {
        return $this->hasOne(FlashSale::class)
            ->where('status', 'active')
            ->where('start_at', '<=', now())
            ->where('end_at', '>', now())
            ->where('stock', '>', 0);
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
     * The currently active price for the product.
     *
     * Rule:
     * - When there is an active Flash Sale => active_price = flash_sale price.
     * - Otherwise => active_price = regular_price (display price).
     *
     * This is the SINGLE source of truth used everywhere a price is shown so a
     * flash sale always affects every page (shop, featured, homepage, search,
     * detail, cart, checkout) consistently. The regular price is never mutated.
     */
    public function getActivePriceAttribute(): float
    {
        $flashSale = $this->activeFlashSale;

        return $flashSale
            ? (float) $flashSale->sale_price
            : $this->regular_price;
    }

    /**
     * Whether the product currently has an active flash sale.
     */
    public function getHasActiveFlashSaleAttribute(): bool
    {
        return $this->activeFlashSale !== null;
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
