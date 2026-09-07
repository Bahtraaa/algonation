@extends('layouts.app')

@section('title', 'Flash Sale - ALGO NATION')

@section('content')
    <section class="relative isolate overflow-hidden bg-ink py-20 text-white sm:py-28">
        <img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=2200&q=85" alt="Koleksi fashion ALGO NATION" class="absolute inset-0 -z-20 h-full w-full object-cover object-[center_35%]">
        <div class="absolute inset-0 -z-10 bg-ink/75"></div>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-primary-soft">Limited time drop</p>
            <h1 class="mt-3 max-w-3xl font-display text-5xl font-extrabold uppercase leading-[0.9] sm:text-7xl lg:text-8xl">Flash Sale</h1>
            <p class="mt-6 max-w-xl text-base text-white/80 sm:text-lg">Ayo dibeli jangan sampai kelewatan.</p>
        </div>
    </section>
    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8" x-data="flashSalePage()" x-init="load()">
        <div class="mb-8 flex items-end justify-between border-b border-slate-200 pb-6 dark:border-white/10"><h2 class="font-display text-3xl font-extrabold uppercase">Promo Berlangsung</h2><p class="text-xs font-bold uppercase tracking-wider text-slate-400" x-text="sales.length + ' promo'"></p></div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4"><template x-for="sale in sales" :key="sale.id"><article class="card overflow-hidden"><a :href="'/products/' + sale.product_id" class="block aspect-[4/5] overflow-hidden"><img :src="sale.image" :alt="sale.name" class="h-full w-full object-cover"></a><div class="p-4"><h3 class="truncate font-bold" x-text="sale.name"></h3><p class="mt-2 text-xs text-slate-400 line-through" x-text="formatRupiah(sale.normal_price)"></p><p class="font-display text-xl font-extrabold text-primary" x-text="formatRupiah(sale.sale_price)"></p><p class="mt-2 text-xs font-bold text-rose-600" x-text="'Diskon ' + sale.discount_percentage + '%'"></p><p class="mt-1 text-xs text-slate-500" x-text="sale.stock + ' stok tersisa'"></p><button @click="$store.cart.add(sale.product_id, null, 1, sale.id)" class="btn-primary mt-4 w-full">Beli Sekarang</button></div></article></template><p x-show="loaded && !sales.length" class="col-span-full py-16 text-center text-sm text-slate-500">Belum ada flash sale aktif.</p></div>
    </section>
@endsection

@push('scripts')
<script>document.addEventListener('alpine:init', () => Alpine.data('flashSalePage', () => ({ sales: [], loaded: false, async load() { this.sales = (await axios.get('/api/flash-sales')).data.data || []; this.loaded = true; } })) );</script>
@endpush
