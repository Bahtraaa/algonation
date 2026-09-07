@extends('layouts.app')

@section('title', 'Belanja - ALGO NATION')

@section('content')
    <div class="min-h-screen bg-white dark:bg-[#1a120d]">
      <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        <div class="animate-fade-up">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-primary dark:text-primary-soft">Shop</p>
            <h1 class="mt-3 font-display text-5xl font-extrabold uppercase leading-none sm:text-7xl">Find your<br>everyday style.</h1>
            <nav class="mt-6 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
                <span>/</span>
                <span class="text-slate-700 dark:text-slate-200">Shop</span>
            </nav>
        </div>

        {{-- Search & filter --}}
        <div class="mt-8 flex flex-col gap-4 sm:flex-row" x-data="{ loading: false }">
            <form action="{{ route('shop') }}" method="GET" class="relative flex-1" @submit="loading = true">
                @if (request('category') && request('category') !== 'all')
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk, kategori, atau deskripsi..."
                    class="input pl-11">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <button type="submit" class="btn-primary absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 text-xs">
                    <span x-show="!loading">Cari</span>
                    <span x-show="loading" class="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"></span>
                </button>
            </form>

            <form action="{{ route('shop') }}" method="GET" class="flex items-center gap-2">
                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <select name="category" onchange="this.form.submit()" class="input w-full sm:w-auto">
                    <option value="all" {{ $selected === 'all' ? 'selected' : '' }}>Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" {{ $selected === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Active filter chips --}}
        @if (request('search') || ($selected !== 'all'))
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <span class="text-xs text-slate-500">Filter aktif:</span>
                @if (request('search'))
                    <span class="badge-neutral">“{{ request('search') }}”</span>
                @endif
                @if ($selected !== 'all')
                    <span class="badge-primary">{{ $selected }}</span>
                @endif
                <a href="{{ route('shop') }}" class="text-xs font-semibold text-rose-500 hover:underline">Hapus semua</a>
            </div>
        @endif

        {{-- Product grid --}}
        <div class="mt-10 grid grid-cols-2 gap-x-4 gap-y-10 sm:grid-cols-3 lg:grid-cols-4">
            @forelse ($products as $product)
                @php
                    $badge = $loop->index % 4 === 0 ? 'NEW' : ($loop->index % 4 === 1 ? 'BEST SELLER' : ($loop->index % 4 === 2 ? 'SALE' : null));
                @endphp
                <article class="group animate-fade-up" style="animation-delay: {{ $loop->index * 50 }}ms">
                    <div class="relative overflow-hidden bg-slate-100 dark:bg-slate-800">
                        <a href="{{ route('products.show', $product) }}" class="relative block aspect-[4/5] overflow-hidden" aria-label="Lihat {{ $product->name }}">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover">
                        </a>
                        @if ($badge)
                            <span class="absolute left-3 top-3 bg-white px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-ink shadow-sm">{{ $badge }}</span>
                        @endif
                        @if ($product->has_active_flash_sale)
                            <span class="absolute right-3 top-3 bg-primary px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow-sm">FLASH SALE</span>
                        @endif
                        <div class="absolute inset-x-3 bottom-3 flex translate-y-2 gap-2 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
                            <button @click="$store.cart.add({{ $product->id }}, null, 1)" @if ($product->is_out_of_stock) disabled @endif class="flex-1 bg-ink px-3 py-2.5 text-[10px] font-bold uppercase tracking-wider text-white transition hover:bg-primary disabled:cursor-not-allowed disabled:opacity-60">
                                {{ $product->is_out_of_stock ? 'Stok Habis' : 'Quick Add' }}
                            </button>
                            <a href="{{ route('products.show', $product) }}" class="flex-1 bg-white px-3 py-2.5 text-center text-[10px] font-bold uppercase tracking-wider text-ink transition hover:bg-primary hover:text-white">View Product</a>
                        </div>
                    </div>
                    <div class="pt-4">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $product->category }}</span>
                            @if ($product->is_out_of_stock)
                                <span class="text-[10px] font-bold uppercase text-rose-500">Habis</span>
                            @elseif ($product->is_low_stock)
                                <span class="text-[10px] font-bold uppercase text-primary">Sisa {{ $product->total_stock }}</span>
                            @endif
                        </div>
                        <a href="{{ route('products.show', $product) }}" class="mt-2 block truncate text-sm font-semibold hover:text-primary dark:hover:text-primary-soft">{{ $product->name }}</a>
                        @include('partials.product-price', ['product' => $product, 'size' => 'sm'])
                    </div>
                </article>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 dark:bg-white/5">
                        <svg class="h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold">Produk tidak ditemukan</h3>
                    <p class="mt-1 text-sm text-slate-500">Coba kata kunci atau kategori lain.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($products->hasPages())
            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif
            </div>
        </div>
@endsection

