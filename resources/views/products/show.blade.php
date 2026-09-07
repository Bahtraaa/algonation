@extends('layouts.app')

@section('title', $product->name . ' - ALGO NATION')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8"
        x-data="productPage({{ $product->id }}, {{ $product->active_price }}, {{ $product->has_active_flash_sale ? $product->active_price : 'null' }})">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-primary">Beranda</a>
            <span>/</span>
            <a href="{{ route('shop') }}" class="hover:text-primary">Belanja</a>
            <span>/</span>
            <a href="{{ route('shop', ['category' => $product->category]) }}" class="hover:text-primary">{{ $product->category }}</a>
            <span>/</span>
            <span class="truncate font-medium text-slate-800 dark:text-slate-200">{{ $product->name }}</span>
        </nav>

        <div class="grid gap-10 lg:grid-cols-2">
            {{-- Image --}}
            <div class="animate-fade-up">
                <div class="glass-strong overflow-hidden rounded-4xl p-3">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="aspect-square w-full rounded-3xl object-cover">
                </div>
            </div>

            {{-- Info --}}
            <div class="animate-fade-up delay-150ms">
                <div class="flex items-center gap-2">
                    <x-status-badge :variant="$product->category_class">{{ $product->category }}</x-status-badge>
                    @if ($product->is_out_of_stock)
                        <span class="badge-danger">Stok Habis</span>
                    @elseif ($product->is_low_stock)
                        <span class="badge-warning">Sisa {{ $product->total_stock }}</span>
                    @else
                        <span class="badge-success">Tersedia</span>
                    @endif
                </div>

                <h1 class="mt-3 font-display text-3xl font-extrabold sm:text-4xl">{{ $product->name }}</h1>

                @if ($product->has_active_flash_sale)
                    <p class="mt-2 text-sm text-slate-400 line-through">Rp {{ number_format($product->regular_price, 0, ',', '.') }}</p>
                    <p class="mt-1 font-display text-2xl font-extrabold text-primary dark:text-primary-soft">
                        Rp <span x-text="formatRupiah(selectedPrice).replace('Rp', '')"></span>
                        <span class="badge-warning align-middle text-xs">FLASH SALE</span>
                    </p>
                @else
                    <p class="mt-4 font-display text-2xl font-extrabold text-primary dark:text-primary-soft">
                        Rp <span x-text="formatRupiah(selectedPrice).replace('Rp', '')"></span>
                    </p>
                @endif

                <p class="mt-5 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                    {{ $product->description ?: 'Deskripsi produk belum tersedia.' }}
                </p>

                {{-- Variants --}}
                @if ($product->variants->isNotEmpty())
                    <div class="mt-6">
                        <h3 class="label">Pilih Varian</h3>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach ($product->variants as $variant)
                                <button type="button"
                                    @click="selectVariant({{ $variant->id }}, {{ $variant->price ?? 'null' }}, {{ $variant->stock }}, '{{ $variant->name }}')"
                                    :class="selectedVariantId === {{ $variant->id }} ? 'border-primary bg-primary/10 ring-2 ring-primary' : 'border-slate-200 hover:border-primary dark:border-white/10'"
                                    @if ($variant->is_out_of_stock) disabled class="opacity-50" @endif
                                    class="rounded-xl border p-3 text-left transition-all">
                                    <span class="block text-sm font-semibold">{{ $variant->name }}</span>
                                    <span class="mt-0.5 block text-xs text-slate-500">
                                        @if ($variant->price)
                                            Rp {{ number_format($variant->price, 0, ',', '.') }}
                                        @else
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        @endif
                                    </span>
                                    <span class="mt-1 block text-[10px] {{ $variant->is_out_of_stock ? 'text-rose-500' : 'text-primary' }}">
                                        {{ $variant->is_out_of_stock ? 'Stok habis' : 'Stok: '.$variant->stock }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Quantity & add to cart --}}
                <div class="mt-6 flex items-center gap-4">
                    <div class="flex items-center rounded-xl border border-slate-300 dark:border-white/15">
                        <button @click="qty = Math.max(1, qty - 1)" class="flex h-12 w-12 items-center justify-center text-lg text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-white/5">−</button>
                        <span class="w-10 text-center font-bold" x-text="qty"></span>
                        <button @click="qty = Math.min(maxQty, qty + 1)" class="flex h-12 w-12 items-center justify-center text-lg text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-white/5">+</button>
                    </div>
                    <button @click="$store.cart.add(productId, selectedVariantId, qty)"
                        @if ($product->is_out_of_stock) disabled @endif
                        class="btn-primary flex-1 btn-lg">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span x-text="maxQty === 0 ? 'Stok Habis' : 'Tambah ke Keranjang'"></span>
                    </button>
                </div>

                {{-- Trust badges --}}
                <div class="mt-8 grid grid-cols-3 gap-3">
                    <div class="rounded-xl bg-slate-50 p-3 text-center dark:bg-white/5">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        <p class="mt-1 text-[10px] font-medium text-slate-500">Gratis Ongkir<br>min. Rp 500rb</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3 text-center dark:bg-white/5">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/><path d="M7 15h4"/></svg>
                        <p class="mt-1 text-[10px] font-medium text-slate-500">Pembayaran Online<br>transfer, e-wallet, QRIS</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3 text-center dark:bg-white/5">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                        <p class="mt-1 text-[10px] font-medium text-slate-500">Kualitas<br>terjamin</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related products --}}
        @if ($related->isNotEmpty())
            <section class="mt-16">
                <h2 class="mb-6 font-display text-2xl font-extrabold">Produk Terkait</h2>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($related as $item)
                        <div class="card group overflow-hidden">
                            <a href="{{ route('products.show', $item) }}" class="block overflow-hidden">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" loading="lazy" class="h-36 w-full object-cover transition-transform duration-500 group-hover:scale-110 sm:h-44">
                            </a>
                            <div class="p-4">
                                <x-status-badge :variant="$item->category_class">{{ $item->category }}</x-status-badge>
                                <a href="{{ route('products.show', $item) }}" class="mt-2 block truncate text-sm font-bold">{{ $item->name }}</a>
                                @include('partials.product-price', ['product' => $item, 'size' => 'sm'])
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function productPage(productId, defaultPrice, salePrice) {
        const hasFlashSale = salePrice !== null;
        return {
            productId,
            defaultPrice,
            hasFlashSale,
            salePrice,
            selectedVariantId: null,
            selectedPrice: hasFlashSale ? salePrice : defaultPrice,
            maxQty: {{ $product->is_out_of_stock ? 0 : max(1, $product->total_stock) }},
            qty: 1,

            selectVariant(id, price, stock, name) {
                this.selectedVariantId = id;
                // Flash sale price overrides every variant price while active.
                this.selectedPrice = this.hasFlashSale ? this.salePrice : (price || this.defaultPrice);
                this.maxQty = Math.max(1, stock);
                this.qty = 1;
            }
        };
    }
</script>
@endpush

