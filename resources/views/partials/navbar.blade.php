{{-- Navbar --}}
<header class="sticky top-0 z-40 no-print" x-data="{ mobileOpen: false }">
    <nav class="border-b border-slate-200/60 bg-white/70 backdrop-blur-xl dark:border-white/10 dark:bg-slate-950/70">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            {{-- Brand --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo-an.png') }}" alt="ALGO NATION" class="h-10 w-10 rounded-xl object-cover shadow-lg shadow-primary/30" onerror="this.onerror=null; this.src='https://placehold.co/100x100/e9f50b/1b1b18?text=AN';">
                <span class="hidden sm:block">
                    <span class="block font-display text-sm font-bold leading-tight tracking-tight">ALGO NATION</span>
                    <span class="block text-[10px] font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Urban Apparel Store</span>
                </span>
            </a>

            {{-- Desktop links --}}
            <div class="hidden items-center gap-1 min-[1025px]:flex">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('shop') }}" class="nav-link {{ request()->routeIs('shop', 'products.show') ? 'active' : '' }}">Belanja</a>
                <div class="ml-2 flex items-center gap-1 border-l border-slate-200 pl-2 dark:border-white/10">
                    <a href="{{ route('featured') }}" class="nav-link {{ request()->routeIs('featured') ? 'active' : '' }}">Unggulan</a>
                    <a href="{{ route('flash-sale') }}" class="nav-link {{ request()->routeIs('flash-sale') ? 'active' : '' }}">Flash Sale</a>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About Us</a>
                </div>
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin Panel</a>
                    @endif
                    <a href="{{ route('orders') }}" class="nav-link {{ request()->routeIs('orders', 'checkout.receipt') ? 'active' : '' }}">Pesanan Saya</a>
                @endauth
            </div>

            {{-- Right actions --}}
            <div class="flex items-center gap-2">
                {{-- Cart button --}}
                <button @click="$store.cart.open = true"
                    class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 transition-all hover:border-primary hover:text-ink dark:border-white/10 dark:bg-slate-900 dark:text-slate-200"
                    aria-label="Cart">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span x-cloak x-show="$store.cart.count > 0"
                        x-text="$store.cart.count"
                        class="absolute -right-1.5 -top-1.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-primary px-1 text-[10px] font-bold text-ink shadow-md"></span>
                </button>

                {{-- Theme toggle --}}
                <button @click="$store.theme.toggle()"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 transition-all hover:border-primary dark:border-white/10 dark:bg-slate-900 dark:text-slate-200"
                    aria-label="Toggle theme">
                    <svg x-cloak x-show="!$store.theme.dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-cloak x-show="$store.theme.dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                {{-- Auth / user --}}
                @auth
                    <div class="relative" x-data="{ accountOpen: false }" @click.outside="accountOpen = false">
                        <button @click="accountOpen = !accountOpen"
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-ink font-bold text-white shadow-md transition-all hover:scale-105 dark:bg-white dark:text-slate-900"
                            aria-label="Account">
                            {{ auth()->user()->initial }}
                        </button>
                        <div x-cloak x-show="accountOpen" x-transition
                            class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl dark:border-white/10 dark:bg-slate-900">
                            <div class="border-b border-slate-100 px-4 py-3 dark:border-white/10">
                                <p class="truncate text-sm font-bold">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                            </div>
                            {{-- Clicking any link closes the dropdown --}}
                            <div class="p-2" @click="accountOpen = false">
                                <a href="{{ route('profile') }}" class="nav-link">Profil Saya</a>
                                <a href="{{ route('orders') }}" class="nav-link">Riwayat Pesanan</a>
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin Panel</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-slate-100 pt-2 dark:border-white/10">
                                    @csrf
                                    <button type="submit" class="nav-link w-full text-left text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="hidden items-center gap-2 min-[1025px]:flex">
                        <a href="{{ route('login') }}" class="btn-ghost btn-sm">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-primary btn-sm">Daftar</a>
                    </div>
                @endauth

                {{-- Hamburger Button --}}
                <button @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" aria-label="Buka menu navigasi"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 min-[1025px]:hidden dark:border-white/10">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu closes automatically when a link is clicked --}}
        <div x-cloak x-show="mobileOpen" x-transition class="border-t border-slate-100 bg-white/95 px-4 py-3 backdrop-blur-xl min-[1025px]:hidden dark:border-white/10 dark:bg-slate-950/95">
            <div class="flex flex-col gap-1" @click="mobileOpen = false">
                <a href="{{ route('home') }}" class="nav-link">Beranda</a>
                <a href="{{ route('shop') }}" class="nav-link">Belanja</a>
                <div class="mt-2 flex flex-col gap-1 border-t border-slate-200 pt-2 dark:border-white/10">
                    <a href="{{ route('featured') }}" class="nav-link">Unggulan</a>
                    <a href="{{ route('flash-sale') }}" class="nav-link">Flash Sale</a>
                    <a href="{{ route('about') }}" class="nav-link">About Us</a>
                </div>
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin Panel</a>
                    @endif
                    <a href="{{ route('profile') }}" class="nav-link">Profil</a>
                    <a href="{{ route('orders') }}" class="nav-link">Pesanan Saya</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary mt-2">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>
</header>