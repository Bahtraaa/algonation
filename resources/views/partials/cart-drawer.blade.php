<div x-cloak x-show="$store.cart.open" x-transition.opacity
    style="display: none;"
    class="fixed inset-0 z-60 bg-slate-900/50 backdrop-blur-sm no-print" @click="$store.cart.open = false"></div>

<aside x-cloak x-show="$store.cart.open"
    style="display: none;"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="translate-x-full"
    class="fixed inset-y-0 right-0 z-65 flex w-full max-w-md flex-col border-l border-slate-200 bg-white shadow-2xl no-print dark:border-white/10 dark:bg-slate-900">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-white/10">
        <h2 class="flex items-center gap-2 font-display text-lg font-bold">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Keranjang
            <span x-text="`(${$store.cart.count})`" class="text-sm font-medium text-slate-500"></span>
        </h2>
        <button @click="$store.cart.open = false" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-white/5">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Items --}}
    <div class="flex-1 overflow-y-auto p-5">
        <template x-if="$store.cart.items.length === 0">
            <div class="flex h-full flex-col items-center justify-center text-center">
                <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 dark:bg-white/5">
                    <svg class="h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <p class="font-semibold">Keranjang Anda kosong</p>
                <p class="mt-1 text-sm text-slate-500">Tambahkan produk favorit Anda!</p>
                <a href="{{ route('shop') }}" @click="$store.cart.open = false" class="btn-primary mt-5">Mulai Belanja</a>
            </div>
        </template>

        <template x-for="item in $store.cart.items" :key="item.key">
            <div class="mb-4 flex gap-4 rounded-2xl border border-slate-100 p-3 dark:border-white/10">
                <img :src="item.image" :alt="item.name" class="h-20 w-20 shrink-0 rounded-xl object-cover">
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold" x-text="item.name"></p>
                            <p x-show="item.variant" class="text-xs text-slate-500" x-text="item.variant"></p>
                            <p class="mt-1 text-sm font-semibold text-primary dark:text-primary-soft" x-text="formatRupiah(item.price)"></p>
                        </div>
                        <button @click="$store.cart.remove(item.key)" class="text-slate-400 hover:text-rose-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                    <div class="mt-2 flex items-center gap-2">
                        <button @click="$store.cart.updateQty(item.key, item.quantity - 1)" class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 dark:border-white/10 dark:hover:bg-white/5" aria-label="Kurangi jumlah"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M5 12h14"/></svg></button>
                        <span class="w-8 text-center text-sm font-bold" x-text="item.quantity"></span>
                        <button @click="$store.cart.updateQty(item.key, item.quantity + 1)" class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 dark:border-white/10 dark:hover:bg-white/5">+</button>
                        <span class="ml-auto text-xs text-slate-500" x-text="`Stok: ${item.stock}`"></span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Footer --}}
    <div class="border-t border-slate-100 p-5 dark:border-white/10">
        <div class="mb-1 flex items-center justify-between text-sm">
            <span class="text-slate-500">Subtotal</span>
            <span class="font-semibold" x-text="formatRupiah($store.cart.subtotal)"></span>
        </div>
        <div class="mb-3 flex items-center justify-between text-sm">
            <span class="text-slate-500">Ongkir</span>
            <span class="font-semibold text-slate-400">Dihitung saat checkout</span>
        </div>
        <div class="mb-4 flex items-center justify-between border-t border-dashed border-slate-200 pt-3 dark:border-white/10">
            <span class="font-bold">Total</span>
            <span class="font-display text-lg font-bold text-primary dark:text-primary-soft" x-text="formatRupiah($store.cart.total)"></span>
        </div>
        <div class="flex gap-2">
            <button @click="$store.cart.open = false" class="btn-outline flex-1">Lanjut Belanja</button>
            <a href="{{ route('checkout') }}" @click="$store.cart.open = false" class="btn-primary flex-1">Checkout</a>
        </div>
        <p class="mt-3 flex items-center justify-center gap-1.5 text-xs text-slate-400"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2zm3 9h3"/></svg> Pembayaran online melalui Midtrans</p>
    </div>
</aside>

