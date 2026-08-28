@extends('layouts.app')

@section('title', 'Flash Sale — ALGO NATION')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div x-data="flashSaleCountdown()" x-init="start()" class="min-h-screen bg-white dark:bg-[#1a120d]">
                {{-- Hero --}}
                <section class="relative overflow-hidden bg-[#171412] text-white">
                    <div class="absolute inset-0">
                        <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=1800&q=85" alt="ALGO NATION flash sale collection" class="h-full w-full object-cover opacity-45">
                        <div class="absolute inset-0 bg-gradient-to-r from-[#171412] via-[#171412]/85 to-[#171412]/35"></div>
                    </div>
                    <div class="relative mx-auto grid max-w-7xl gap-12 px-4 py-20 sm:px-6 lg:grid-cols-[1fr_0.8fr] lg:items-end lg:px-8 lg:py-28">
                        <div class="animate-fade-up">
                            <p class="text-xs font-bold uppercase tracking-[0.3em] text-[#d4a574]">⚡ Flash Sale</p>
                            <h1 class="mt-5 max-w-3xl font-display text-5xl font-extrabold uppercase leading-[0.88] sm:text-7xl lg:text-8xl">Big style.<br><span class="text-[#d4a574]">Small price.</span></h1>
                            <p class="mt-7 max-w-xl text-base leading-relaxed text-stone-300">Dapatkan produk pilihan ALGO NATION dengan harga spesial sebelum waktunya habis.</p>
                            <a href="#flash-products" class="btn-primary mt-8">Shop Flash Sale <span aria-hidden="true">↗</span></a>
                        </div>
                        <div class="border-l border-white/30 pl-6 sm:pl-8">
                            <p class="text-xs font-bold uppercase tracking-[0.3em] text-stone-300">Ends In</p>
                            <div class="mt-4 flex items-end gap-2 font-display text-4xl font-extrabold tabular-nums sm:text-6xl">
                                <span x-text="time.hours">05</span><span class="text-[#d4a574]">:</span><span x-text="time.minutes">42</span><span class="text-[#d4a574]">:</span><span x-text="time.seconds">18</span>
                            </div>
                            <div class="mt-2 flex gap-8 text-[10px] font-bold uppercase tracking-wider text-stone-400"><span>Hr</span><span>Min</span><span>Sec</span></div>
                        </div>
                    </div>
                </section>

                {{-- Promo banner --}}
                <section class="border-b border-slate-200 bg-[#e9ddce] dark:border-white/10 dark:bg-[#2a1b16]">
                    <div class="mx-auto flex max-w-7xl flex-col gap-5 px-4 py-8 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8">
                        <div class="flex items-center gap-5"><span class="font-display text-4xl font-extrabold text-primary">50%</span><div><p class="font-display text-xl font-extrabold uppercase">Up to 50% off</p><p class="text-sm text-slate-600 dark:text-slate-300">Limited time only. Don't miss your favorite pieces.</p></div></div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary">⚡ Limited Stock</p>
                    </div>
                </section>

                {{-- Sticky urgency countdown --}}
                <div class="sticky top-[65px] z-30 border-b border-slate-200 bg-white/95 backdrop-blur dark:border-white/10 dark:bg-[#1a120d]/95">
                    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary">⚡ Flash Sale Ends In</p>
                        <p class="font-display text-lg font-extrabold tabular-nums text-ink dark:text-white"><span x-text="time.hours">05</span>:<span x-text="time.minutes">42</span>:<span x-text="time.seconds">18</span></p>
                    </div>
                </div>

                {{-- Products --}}
                <section id="flash-products" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
                    <div class="mb-8 flex items-end justify-between border-b border-slate-200 pb-6 dark:border-white/10"><div><p class="text-xs font-bold uppercase tracking-[0.3em] text-primary dark:text-primary-soft">03 / The Drop</p><h2 class="mt-2 font-display text-3xl font-extrabold uppercase sm:text-4xl">Flash Sale Products</h2></div><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Limited quantities</p></div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-10 sm:grid-cols-3 lg:grid-cols-4">
                        @forelse ($products as $product)
                            @php
                                $regularPrice = $product->display_price;
                                $discountPercent = 30;
                                $salePrice = $regularPrice * (1 - $discountPercent / 100);
                                $soldPercent = min(95, max(35, 100 - ($product->total_stock * 7)));
                                $rating = number_format(4.5 + (($loop->index % 5) / 10), 1);
                            @endphp
                            <article class="group animate-fade-up" style="animation-delay: {{ $loop->index * 50 }}ms">
                                <div class="relative overflow-hidden bg-slate-100 dark:bg-slate-800">
                                    <a href="{{ route('products.show', $product) }}" class="block aspect-[4/5] overflow-hidden" aria-label="Lihat {{ $product->name }}"><img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"></a>
                                    <span class="absolute left-3 top-3 bg-rose-600 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white">-{{ $discountPercent }}%</span>
                                    <button type="button" aria-label="Tambah {{ $product->name }} ke wishlist" class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center bg-white text-lg text-ink shadow-sm transition hover:bg-primary hover:text-white">♡</button>
                                    <div class="absolute inset-x-3 bottom-3 flex translate-y-2 gap-2 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100"><button @click="$store.cart.add({{ $product->id }}, null, 1)" @if ($product->is_out_of_stock) disabled @endif class="flex-1 bg-ink px-3 py-2.5 text-[10px] font-bold uppercase tracking-wider text-white transition hover:bg-primary disabled:opacity-60">{{ $product->is_out_of_stock ? 'Stok Habis' : 'Add to Cart' }}</button><a href="{{ route('products.show', $product) }}" class="flex-1 bg-white px-3 py-2.5 text-center text-[10px] font-bold uppercase tracking-wider text-ink transition hover:bg-primary hover:text-white">View</a></div>
                                </div>
                                <div class="pt-4"><div class="flex items-center justify-between gap-2"><p class="truncate text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $product->category }}</p><p class="shrink-0 text-xs text-amber-500">★ {{ $rating }}</p></div><a href="{{ route('products.show', $product) }}" class="mt-2 block truncate text-sm font-semibold hover:text-primary">{{ $product->name }}</a><p class="mt-2 text-xs text-slate-400 line-through">Rp {{ number_format($regularPrice, 0, ',', '.') }}</p><p class="text-base font-extrabold text-rose-600">Rp {{ number_format($salePrice, 0, ',', '.') }}</p><div class="mt-4"><div class="mb-1 flex justify-between text-[10px] font-bold uppercase tracking-wider text-slate-400"><span>Stock progress</span><span>{{ $soldPercent }}% sold</span></div><div class="h-1.5 bg-slate-200 dark:bg-white/10"><div class="h-full bg-rose-600" style="width: {{ $soldPercent }}%"></div></div></div></div>
                            </article>
                        @empty
                            <p class="col-span-full py-16 text-center text-sm text-slate-500">Belum ada produk flash sale.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    @endsection

    @push('scripts')
    <script>
        function flashSaleCountdown() {
            return {
                remaining: (5 * 60 * 60) + (42 * 60) + 18,
                time: { hours: '05', minutes: '42', seconds: '18' },
                start() {
                    this.update();
                    setInterval(() => { this.remaining = Math.max(0, this.remaining - 1); this.update(); }, 1000);
                },
                update() {
                    const hours = Math.floor(this.remaining / 3600);
                    const minutes = Math.floor((this.remaining % 3600) / 60);
                    const seconds = this.remaining % 60;
                    this.time = { hours: String(hours).padStart(2, '0'), minutes: String(minutes).padStart(2, '0'), seconds: String(seconds).padStart(2, '0') };
                }
            };
        }
    </script>
    @endpush
