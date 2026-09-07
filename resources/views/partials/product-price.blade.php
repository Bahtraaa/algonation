@props(['product', 'size' => 'sm', 'align' => 'left'])

@php
    $hasSale  = $product instanceof \App\Models\Product && $product->has_active_flash_sale;
    $regular  = $product->regular_price ?? $product->display_price ?? 0;
    $active   = $product->active_price ?? $product->display_price ?? 0;
    $sizeCls  = $size === 'lg' ? 'text-lg' : ($size === 'xl' ? 'text-xl' : 'text-sm');
    $alignCls = $align === 'center' ? 'text-center' : 'text-left';
@endphp

<p class="mt-1 {{ $alignCls }}">
    @if ($hasSale)
        <span class="text-xs text-slate-400 line-through {{ $size === 'xl' ? '' : 'block' }}">
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