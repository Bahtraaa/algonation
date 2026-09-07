@extends('layouts.app')

@section('title', 'ALGO NATION')

@section('content')
    {{-- Hero Section with Auto-Sliding Background --}}
    <section x-data="{
            activeSlide: 0,
            slides: [
                'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=1600&q=80',
                'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1600&q=80',
                'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=1600&q=80'
            ],
            timer: null,
            startAutoSlide() {
                this.stopAutoSlide();
                this.timer = setInterval(() => {
                    this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                }, 4000);
            },
            stopAutoSlide() {
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            }
        }"
        x-init="startAutoSlide()"
        @mouseenter="stopAutoSlide()"
        @mouseleave="startAutoSlide()"
        class="relative overflow-hidden py-12 sm:py-20 lg:py-24">

        {{-- Background Auto-Sliding Images & Overlay --}}
        <div class="absolute inset-0 z-0 overflow-hidden">
            <div class="flex h-full w-full transition-transform duration-1000 ease-in-out"
                :style="`transform: translateX(-${activeSlide * 100}%)`">
                <template x-for="(image, index) in slides" :key="index">
                    <div class="h-full w-full flex-shrink-0 relative">
                        <img :src="image" alt="Hero Background" class="h-full w-full object-cover">
                    </div>
                </template>
            </div>
            <!-- Dark Gradient Overlay for text contrast -->
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-950/75 to-slate-950/60 dark:from-slate-950/95 dark:via-slate-950/85 dark:to-slate-950/70"></div>
            <div class="absolute inset-0 bg-slate-950/30 backdrop-blur-[2px]"></div>
        </div>

        {{-- Ambient Glow --}}
        <div class="pointer-events-none absolute -left-40 -top-40 z-10 h-96 w-96 rounded-full bg-primary/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-40 top-20 z-10 h-96 w-96 rounded-full bg-[#6b4423]/20 blur-3xl"></div>

        <div class="relative z-10 mx-auto grid max-w-7xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div class="animate-fade-up text-white">
                <span class="badge-primary mb-5 inline-flex items-center gap-2">
                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-primary"></span>
                    Urban Apparel Store
                </span>
                <h1 class="font-display text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl text-white">
                    Tampilkan Gaya
                    <span class="text-gradient">Terbaikmu</span><br>
                    dengan Fashion Pilihan
                </h1>
                <p class="mt-5 max-w-xl text-base text-slate-200 sm:text-lg">
                    Temukan koleksi pakaian dan aksesori trendi, mulai dari streetwear, denim, outerwear hingga sepatu dan tas. Kualitas premium, harga bersahabat, dan pembayaran online.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('shop') }}" class="btn-primary btn-lg shadow-xl shadow-primary/30">
                        Belanja Sekarang
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="#categories" class="btn-outline btn-lg text-white border-white/30 hover:bg-white/10">Jelajahi Kategori</a>
                </div>
                <div class="mt-10 flex flex-wrap gap-8">
                    <div>
                        <p class="font-display text-2xl font-extrabold text-white">8</p>
                        <p class="text-xs text-slate-300">Kategori Produk</p>
                    </div>
                    <div>
                        <p class="font-display text-2xl font-extrabold text-white">100%</p>
                        <p class="text-xs text-slate-300">Original & Aman</p>
                    </div>
                    <div>
                        <p class="font-display text-2xl font-extrabold text-white">Online</p>
                        <p class="text-xs text-slate-300">Pembayaran Online</p>
                    </div>
                </div>
            </div>

            <div class="relative z-10 animate-fade-up delay-150ms">
                <div class="animate-float">
                    <div class="relative mx-auto max-w-md">
                        <div class="absolute -inset-4 rounded-4xl bg-linear-to-br from-primary via-[#6b4423] to-[#4a2f1b] opacity-40 blur-2xl"></div>
                        <div class="glass-strong relative overflow-hidden rounded-4xl p-6 border-2 border-primary/50 bg-slate-900/80 backdrop-blur-xl shadow-2xl shadow-primary/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-medium text-slate-300">Koleksi Terbaru</p>
                                    <h3 class="font-display text-lg font-bold text-white">PAKAIAN TERBAIK 2026</h3>
                                </div>
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-ink shadow-md shadow-primary/30">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </span>
                            </div>
                            <div class="mt-5 grid grid-cols-2 gap-3">

                                <div class="rounded-2xl bg-gradient-to-br from-primary/30 via-[#6b4423]/25 to-slate-950/70 p-3 border border-primary/30 backdrop-blur-md hover:border-primary transition-all duration-300 shadow-lg">
                                    <div class="mb-2 h-24 overflow-hidden rounded-xl border border-primary/30">
                                        <img src="https://down-id.img.susercontent.com/file/id-11134207-8224o-mk7h4o8vqio783" alt="Streetwear Essentials" class="h-full w-full object-cover transition-transform duration-300 hover:scale-110">
                                    </div>
                                    <p class="text-xs font-semibold text-white">Outwear Stylish</p>
                                    <p class="text-[10px] text-slate-300">Mulai dari <del class="text-slate-400">285ribu</del> 250ribu saja</p>
                                </div>

                                <div class="rounded-2xl bg-gradient-to-br from-[#5c3d25]/30 via-primary/20 to-slate-950/70 p-3 border border-primary/30 backdrop-blur-md hover:border-primary transition-all duration-300 shadow-lg">
                                    <div class="mb-2 h-24 overflow-hidden rounded-xl border border-primary/30">
                                        <img src="https://down-id.img.susercontent.com/file/id-11134207-822wi-mnz1uohquk8w07" alt="Denim & Jeans" class="h-full w-full object-cover transition-transform duration-300 hover:scale-110">
                                    </div>
                                    <p class="text-xs font-semibold text-white">Denim & Jeans</p>
                                    <p class="text-[10px] text-slate-300">Mulai Rp 350rb</p>
                                </div>

                                <div class="rounded-2xl bg-gradient-to-br from-primary/30 via-[#4a2f1b]/25 to-slate-950/70 p-3 border border-primary/30 backdrop-blur-md hover:border-primary transition-all duration-300 shadow-lg">
                                    <div class="mb-2 h-24 overflow-hidden rounded-xl border border-primary/30">
                                        <img src="https://down-id.img.susercontent.com/file/id-11134207-822wk-mnf3g2aeat4zf1" alt="Outerwear Stylish" class="h-full w-full object-cover transition-transform duration-300 hover:scale-110">
                                    </div>
                                    <p class="text-xs font-semibold text-white">Footwear</p>
                                    <p class="text-[10px] text-slate-300">Mulai Rp 500rb</p>
                                </div>

                                <div class="rounded-2xl bg-gradient-to-br from-[#6b4423]/30 via-primary/20 to-slate-950/70 p-3 border border-primary/30 backdrop-blur-md hover:border-primary transition-all duration-300 shadow-lg">
                                    <div class="mb-2 h-24 overflow-hidden rounded-xl border border-primary/30">
                                        <img src="https://down-id.img.susercontent.com/file/id-11134207-7ra0l-mdhm65q11bmadf" alt="Aksesori Fashion" class="h-full w-full object-cover transition-transform duration-300 hover:scale-110">
                                    </div>
                                    <p class="text-xs font-semibold text-white">Accessories Fashion</p>
                                    <p class="text-[10px] text-slate-300">Mulai Rp 75rb</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Background Slider Indicators --}}
        <div class="absolute bottom-4 left-1/2 z-20 flex -translate-x-1/2 gap-2.5">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="activeSlide = index; startAutoSlide()"
                    :class="activeSlide === index ? 'w-8 bg-primary' : 'w-2.5 bg-white/40 hover:bg-white'"
                    class="h-2.5 rounded-full transition-all duration-300"
                    :aria-label="`Slide background ${index + 1}`"></button>
            </template>
        </div>
    </section>



    {{-- Categories --}}
    <section id="categories" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-primary dark:text-primary-soft">Kategori Produk</p>
                <h2 class="mt-1 font-display text-2xl font-extrabold sm:text-3xl">Belanja Berdasarkan Kategori</h2>
            </div>
            <a href="{{ route('shop') }}" class="btn-ghost btn-sm hidden sm:inline-flex">Lihat Semua</a>
        </div>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($categories as $category)
                <a href="{{ route('shop', ['category' => $category]) }}"
                    class="card group p-6 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-linear-to-br from-primary/30 to-[#6b4423]/30 text-2xl font-bold text-ink transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                        {{ strtoupper(substr($category, 0, 1)) }}
                    </div>
                    <h3 class="font-semibold transition-colors group-hover:text-primary dark:group-hover:text-primary-soft">{{ $category }}</h3>
                    <p class="mt-1 text-xs text-slate-500">Lihat koleksi →</p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Database-backed flash sale --}}
    @if ($flashSales->isNotEmpty())
        <section class="relative overflow-hidden bg-ink py-14 text-white sm:py-20" x-data="flashSaleCountdown('{{ $flashSales->first()->end_at->toIso8601String() }}')" x-init="start()">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col justify-between gap-5 border-b border-white/15 pb-6 sm:flex-row sm:items-end">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.3em] text-primary-soft">Limited time drop</p>
                        <h2 class="mt-2 font-display text-4xl font-extrabold uppercase sm:text-6xl">Flash Sale</h2>
                    </div>
                    <p class="font-display text-xl font-bold text-primary-soft" x-text="label"></p>
                </div>
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($flashSales as $sale)
                        <article class="overflow-hidden rounded-2xl border border-white/10 bg-white/5">
                            <a href="{{ route('products.show', $sale->product) }}" class="block aspect-[4/5] overflow-hidden">
                                <img src="{{ $sale->product->image_url }}" alt="{{ $sale->product->name }}" class="h-full w-full object-cover">
                            </a>
                            <div class="p-4">
                                <h3 class="truncate font-bold">{{ $sale->product->name }}</h3>
                                <p class="mt-2 text-sm text-white/45 line-through">Rp {{ number_format($sale->normal_price, 0, ',', '.') }}</p>
                                <p class="font-display text-xl font-extrabold text-primary-soft">Rp {{ number_format($sale->sale_price, 0, ',', '.') }}</p>
                                <div class="mt-3 flex items-center justify-between text-xs text-white/70">
                                    <span>Diskon {{ rtrim(rtrim(number_format($sale->discount_percentage, 2, ',', '.'), '0'), ',') }}%</span>
                                    <span>{{ $sale->stock }} tersisa</span>
                                </div>
                                <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-white/15"><div class="h-full bg-primary" style="width: {{ min(100, $sale->stock / max(1, $sale->stock + 10) * 100) }}%"></div></div>
                                <button @click="$store.cart.add({{ $sale->product_id }}, null, 1, {{ $sale->id }})" class="btn-primary mt-4 w-full" {{ $sale->stock < 1 ? 'disabled' : '' }}>{{ $sale->stock < 1 ? 'Habis' : 'Beli Sekarang' }}</button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured products --}}
    <section id="featured" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between">
            <div>
                <p id="flashsale" class="text-xs font-bold uppercase tracking-widest text-primary dark:text-primary-soft">Produk Unggulan & Flash Sale</p>
                <h2 class="mt-1 font-display text-2xl font-extrabold sm:text-3xl">Terlaris & Favorit</h2>
            </div>
            <a href="{{ route('shop') }}" class="btn-primary btn-sm">Lihat Semua Produk</a>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @forelse ($featured as $product)
                <div class="card group overflow-hidden">
                    <a href="{{ route('products.show', $product) }}" class="block overflow-hidden">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy"
                            class="h-40 w-full object-cover transition-transform duration-500 group-hover:scale-110 sm:h-48">
                    </a>
                    <div class="p-4">
                        <x-status-badge :variant="$product->category_class">{{ $product->category }}</x-status-badge>
                        <a href="{{ route('products.show', $product) }}" class="mt-2 block truncate text-sm font-bold hover:text-primary dark:hover:text-primary-soft">{{ $product->name }}</a>
                        @include('partials.product-price', ['product' => $product, 'size' => 'sm'])
                        <div class="mt-3">
                            <button @click="$store.cart.add({{ $product->id }}, null, 1)"
                                @if ($product->is_out_of_stock) disabled @endif
                                class="btn-primary btn-sm w-full">
                                @if ($product->is_out_of_stock)
                                    Stok Habis
                                @else
                                    + Keranjang
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <p class="text-lg font-semibold">Belum ada produk.</p>
                    <p class="mt-1 text-sm text-slate-500">Segera hadir koleksi terbaru kami!</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Lookbook --}}
    <section class="relative overflow-hidden bg-[#171412] text-white">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 py-20 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-center lg:px-8 lg:py-28">
            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-[#d4a574]">08 / Fashion Inspiration</p>
                <h2 class="mt-5 font-display text-6xl font-extrabold uppercase leading-[0.88] sm:text-8xl">Wear<br><span class="text-[#d4a574]">Your Way</span></h2>
                <p class="mt-7 max-w-sm text-base leading-relaxed text-stone-300">Explore the latest looks from ALGO NATION.</p>
                <a href="{{ route('featured') }}" class="btn-primary mt-8">View Lookbook <span aria-hidden="true">→</span></a>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div class="mt-10 aspect-[3/4] overflow-hidden bg-stone-800">
                    <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=700&q=85" alt="Inspirasi outfit ALGO NATION" class="h-full w-full object-cover grayscale transition duration-500 hover:scale-105 hover:grayscale-0">
                </div>
                <div class="aspect-[3/4] overflow-hidden bg-stone-800">
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=700&q=85" alt="Inspirasi footwear ALGO NATION" class="h-full w-full object-cover transition duration-500 hover:scale-105">
                </div>
                <div class="mt-20 aspect-[3/4] overflow-hidden bg-stone-800">
                    <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=700&q=85" alt="Inspirasi outerwear ALGO NATION" class="h-full w-full object-cover grayscale transition duration-500 hover:scale-105 hover:grayscale-0">
                </div>
            </div>
        </div>
    </section>

    {{-- Shopping confidence --}}
    <section class="border-y border-slate-200 bg-[#efe5d7] dark:border-white/10 dark:bg-[#241812]">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
            <div class="flex flex-col justify-between gap-6 border-b border-primary/20 pb-8 sm:flex-row sm:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-primary dark:text-primary-soft">09 / The Algo Standard</p>
                    <h2 class="mt-4 font-display text-4xl font-extrabold uppercase leading-none sm:text-6xl">Shop with<br>confidence.</h2>
                </div>
                <a href="{{ route('about') }}" class="btn-dark">Our Story <span aria-hidden="true">→</span></a>
            </div>
            <div class="grid gap-8 pt-8 sm:grid-cols-3">
                <div>
                    <p class="font-display text-3xl font-extrabold text-primary">01</p>
                    <h3 class="mt-4 font-display text-lg font-bold uppercase">Curated Quality</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Pilihan fashion dengan material dan kualitas yang nyaman digunakan setiap hari.</p>
                </div>
                <div>
                    <p class="font-display text-3xl font-extrabold text-primary">02</p>
                    <h3 class="mt-4 font-display text-lg font-bold uppercase">Easy Everyday Style</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Desain modern yang mudah dipadukan untuk berbagai versi dirimu.</p>
                </div>
                <div>
                    <p class="font-display text-3xl font-extrabold text-primary">03</p>
                    <h3 class="mt-4 font-display text-lg font-bold uppercase">Pembayaran Online</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Bayar mudah & aman lewat transfer bank, e-wallet, QRIS, atau kartu kredit.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Why us --}}
    <section id="about" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="glass-strong grid gap-8 rounded-4xl p-8 sm:grid-cols-3 lg:p-12">
            <div class="text-center">
                <span class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary text-ink">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <h3 class="font-semibold">Kualitas Terjamin</h3>
                <p class="mt-1 text-sm text-slate-500">Material premium & tahan lama untuk rumah Anda.</p>
            </div>
            <div class="text-center">
                <span class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary text-ink">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                </span>
                <h3 class="font-semibold">Pengiriman Cepat</h3>
                <p class="mt-1 text-sm text-slate-500">Estimasi pengiriman jelas ke seluruh Indonesia.</p>
            </div>
            <div class="text-center">
                <span class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary text-ink">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2m5-4h6m-3-3v6m-9-7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2V7z"/></svg>
                </span>
                <h3 class="font-semibold">Pembayaran Online</h3>
                <p class="mt-1 text-sm text-slate-500">Bayar mudah lewat transfer bank, e-wallet, QRIS, atau kartu kredit.</p>
            </div>
        </div>
    </section>
@endsection

