{{-- Footer --}}
<footer class="border-t border-slate-200 bg-white/70 backdrop-blur-xl dark:border-white/10 dark:bg-slate-950/70">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 border-b border-slate-200 pb-10 sm:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr_1fr] dark:border-white/10">
            <div>
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo-an.png') }}" alt="ALGO NATION" class="h-9 w-9 rounded-lg object-cover" onerror="this.onerror=null; this.src='https://placehold.co/100x100/e9f50b/1b1b18?text=AN';">
                    <span class="font-display text-sm font-extrabold tracking-wide">ALGO NATION</span>
                </div>
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-slate-500 dark:text-slate-400">Fashion for every version of you.</p>
            </div>
            <div>
                <h2 class="text-xs font-bold uppercase tracking-[0.2em]">Shop</h2>
                <div class="mt-4 space-y-2 text-sm text-slate-500 dark:text-slate-400">
                    <a href="{{ route('shop') }}" class="block hover:text-primary">New Arrivals</a>
                    <a href="{{ route('featured') }}" class="block hover:text-primary">Best Sellers</a>
                    <a href="{{ route('shop', ['category' => 'Men']) }}" class="block hover:text-primary">Men</a>
                    <a href="{{ route('shop', ['category' => 'Women']) }}" class="block hover:text-primary">Women</a>
                    <a href="{{ route('flash-sale') }}" class="block hover:text-primary">Sale</a>
                </div>
            </div>
            <div>
                <h2 class="text-xs font-bold uppercase tracking-[0.2em]">Help</h2>
                <div class="mt-4 space-y-2 text-sm text-slate-500 dark:text-slate-400">
                    <a href="mailto:hello@algonation.com" class="block hover:text-primary">Contact Us</a>
                    <a href="{{ route('about') }}#faq" class="block hover:text-primary">FAQ</a>
                    <a href="{{ route('about') }}#shipping" class="block hover:text-primary">Shipping</a>
                    <a href="{{ route('about') }}#returns" class="block hover:text-primary">Returns</a>
                </div>
            </div>
            <div>
                <h2 class="text-xs font-bold uppercase tracking-[0.2em]">Follow Us</h2>
                <div class="mt-4 space-y-2 text-sm text-slate-500 dark:text-slate-400">
                    <a href="#" class="block hover:text-primary">Instagram</a>
                    <a href="#" class="block hover:text-primary">TikTok</a>
                    <a href="#" class="block hover:text-primary">Facebook</a>
                </div>
            </div>
        </div>
        <div class="flex flex-col items-center justify-between gap-3 pt-6 text-xs text-slate-500 sm:flex-row dark:text-slate-400">
            <p>© {{ date('Y') }} ALGO NATION. Fashion for every version of you.</p>
            <p>Pembayaran online tersedia di seluruh Indonesia.</p>
        </div>
    </div>
</footer>