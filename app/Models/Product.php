<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];

    /**
     * Get the variants for the product.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the transaction details for the product.
     */
    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Total sellable stock = base stock + sum of variant stock.
     */
    public function getTotalStockAttribute(): int
    {
        return $this->stock + $this->variants()->sum('stock');
    }

    /**
     * Whether the product is low on stock (<= 5 total units).
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->total_stock <= 5;
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
        return match ($this->category) {
            'Formal Wear' => 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft',
            'Bottoms'   => 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft',
            'Outerwear' => 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft',
            'Footwear'  => 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft',
            'Accessories'=> 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft',
            'Bags'      => 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft',
            'Hats'      => 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft',
            'Casual T-Shirt' => 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft',
            default     => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        };
    }
}

