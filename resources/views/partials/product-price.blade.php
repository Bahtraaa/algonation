@props(['product', 'size' => 'sm'])

@php
    // Satu sumber harga: $product->final_price (alias: active_price).
    // Harga asli: $product->price (alias: original_price).
    // Produk Unggulan memakai $featuredProduct->product->final_price,
    // tidak pernah dari kolom harga di tabel featured_products.
    $hasSale  = (bool) ($product->has_flash_sale ?? $product->has_active_flash_sale ?? false);
    $regular  = $product->original_price ?? $product->price ?? $product->regular_price ?? $product->display_price ?? 0;
    $active   = $product->final_price ?? $product->active_price ?? $product->display_price ?? 0;
    $sizeCls  = $size === 'lg' ? 'text-lg' : ($size === 'xl' ? 'text-xl' : 'text-sm');
@endphp

<p class="mt-1">
    @if ($hasSale)
        <span class="text-xs text-slate-400 line-through {{ $size === 'xl' ? '' : 'block' }}" style="text-decoration: line-through;">
            Rp {{ number_format($regular, 0, ',', '.') }}
        </span>
        <span class="block {{ $sizeCls }} font-bold text-primary dark:text-primary-soft">
            Rp {{ number_format($active, 0, ',', '.') }}
        </span>
    @else
        <span class="{{ $sizeCls }} font-bold text-ink dark:text-white">
            Rp {{ number_format($active, 0, ',', '.') }}
        </span>
    @endif
</p>