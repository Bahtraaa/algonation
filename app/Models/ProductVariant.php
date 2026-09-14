<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'color',
        'size',
        'sku',
        'stock',
        'price',
        'image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'float',
        ];
    }

    /**
     * Get the product that owns the variant.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Sinkronisasi otomatis kolom `name` lama dari color/size agar
     * seluruh kode lama (cart, checkout, detail produk) tetap jalan.
     */
    protected static function booted(): void
    {
        static::saving(function (ProductVariant $variant) {
            if (empty($variant->name)) {
                $parts = array_filter([$variant->color, $variant->size]);
                $variant->name = $parts ? implode(' - ', $parts) : 'Variant';
            }
            if (empty($variant->color) && empty($variant->size) && ! empty($variant->name)) {
                $variant->color = $variant->name;
            }
            if ($variant->sku === '') {
                $variant->sku = null;
            }
        });

        // Hapus file gambar dari storage saat variant dihapus agar
        // tidak meninggalkan file yang tidak digunakan.
        static::deleting(function (ProductVariant $variant) {
            if ($variant->image && ! str_starts_with($variant->image, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($variant->image);
            }
        });
    }

    /**
     * Label tampilan variant: "Hitam - M" (fallback ke name lama).
     */
    public function getDisplayNameAttribute(): string
    {
        $parts = array_filter([$this->color, $this->size]);

        if (! empty($parts)) {
            return implode(' - ', $parts);
        }

        return (string) ($this->name ?? 'Variant');
    }

    /**
     * Whether this variant is out of stock.
     */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stock <= 0;
    }

    /**
     * URL publik gambar variant (fallback ke gambar produk bila kosong).
     */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }

        return asset('storage/'.$this->image);
    }

    /**
     * Gambar efektif variant: gambar variant sendiri, atau gambar
     * produk sebagai fallback bila variant belum punya gambar.
     */
    public function getEffectiveImageUrlAttribute(): string
    {
        return $this->image_url ?? $this->product?->image_url
            ?? 'https://placehold.co/600x400/e9f50b/1b1b18?text=No+Image';
    }
}

