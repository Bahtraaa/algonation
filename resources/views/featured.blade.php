@extends('layouts.app')

@section('title', 'Unggulan - ALGO NATION')

@section('content')
    <div class="min-h-screen bg-white dark:bg-[#1a120d]">
        <section class="border-b border-slate-200 bg-[#efe5d7] dark:border-white/10 dark:bg-[#241812]">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[1fr_0.7fr] lg:items-end lg:px-8 lg:py-24">
                <div class="animate-fade-up">
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-primary dark:text-primary-soft">Featured Collection</p>
                    <h1 class="mt-5 max-w-4xl font-display text-5xl font-extrabold uppercase leading-[0.9] sm:text-7xl lg:text-8xl">The pieces<br>that define<br><span class="text-primary dark:text-primary-soft">Algo Nation.</span></h1>
                    <p class="mt-7 max-w-xl text-base leading-relaxed text-slate-700 dark:text-slate-300">Koleksi pilihan dengan desain, kualitas, dan karakter terbaik dari ALGO NATION.</p>
                    <a href="{{ route('shop') }}" class="btn-dark mt-8">Shop Featured <span aria-hidden="true">→</span></a>
                </div>
                <div class="relative hidden aspect-[4/5] overflow-hidden bg-ink lg:block">
                    <img src="{{ asset('images/sepatu-nike.jpg') }}" alt="Featured fashion collection ALGO NATION" class="h-full w-full object-cover opacity-90">
                    <div class="absolute inset-0 bg-gradient-to-t from-ink/70 via-transparent to-transparent"></div>
                    <p class="absolute bottom-5 left-5 text-xs font-bold uppercase tracking-[0.25em] text-white">Selected / 2026</p>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <div class="mb-8 flex items-end justify-between border-b border-slate-200 pb-6 dark:border-white/10">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-primary dark:text-primary-soft">01 / The Edit</p>
                    <h2 class="mt-2 font-display text-3xl font-extrabold uppercase sm:text-4xl">Featured Pieces</h2>
                </div>
                <a href="{{ route('shop') }}" class="text-xs font-bold uppercase tracking-wider text-slate-500 hover:text-primary">View All</a>
            </div>
            <div class="grid grid-cols-2 gap-x-4 gap-y-10 sm:grid-cols-3 lg:grid-cols-4">
            @forelse ($products as $product)
                @php
                    $badge = $loop->index % 3 === 0 ? 'BEST SELLER' : ($loop->index % 3 === 1 ? 'NEW' : 'SALE');
                @endphp
                <article class="group animate-fade-up" style="animation-delay: {{ $loop->index * 50 }}ms">
                    <div class="relative overflow-hidden bg-slate-100 dark:bg-slate-800">
                        <a href="{{ route('products.show', $product) }}" class="relative block aspect-[4/5] overflow-hidden" aria-label="Lihat {{ $product->name }}">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover">
                        </a>
                        <span class="absolute left-3 top-3 bg-white px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-ink shadow-sm">{{ $badge }}</span>
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
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $product->category }}</p>
                        <a href="{{ route('products.show', $product) }}" class="mt-2 block truncate text-sm font-semibold hover:text-primary dark:hover:text-primary-soft">{{ $product->name }}</a>
                        @include('partials.product-price', ['product' => $product, 'size' => 'sm'])
                    </div>
                </article>
            @empty
                <p class="col-span-full py-16 text-center text-sm text-slate-500">Belum ada produk unggulan.</p>
            @endforelse
            </div>
        </section>
    </div>
@endsection
