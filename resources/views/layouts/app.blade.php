<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ALGO NATION') — Fashion Marketplace</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- CSS Global untuk mencegah semua FOUC (Flash of Unstyled Content) Alpine.js --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body x-data class="flex min-h-screen flex-col">
    @include('partials.toasts')

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
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open"
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-ink font-bold text-white shadow-md transition-all hover:scale-105 dark:bg-white dark:text-slate-900"
                                aria-label="Account">
                                {{ auth()->user()->initial }}
                            </button>
                            <div x-cloak x-show="open" x-transition
                                class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl dark:border-white/10 dark:bg-slate-900">
                                <div class="border-b border-slate-100 px-4 py-3 dark:border-white/10">
                                    <p class="truncate text-sm font-bold">{{ auth()->user()->name }}</p>
                                    <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                                </div>
                                {{-- Ditambahkan @click="open = false" agar dropdown nutup saat link diklik --}}
                                <div class="p-2" @click="open = false">
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

            {{-- Mobile menu --}}
            <div x-cloak x-show="mobileOpen" x-transition class="border-t border-slate-100 bg-white/95 px-4 py-3 backdrop-blur-xl min-[1025px]:hidden dark:border-white/10 dark:bg-slate-950/95">
                {{-- Ditambahkan @click="mobileOpen = false" agar menu nutup otomatis saat salah satu link diklik --}}
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

    {{-- Main content --}}
    <main class="flex-1">
        @yield('content')
    </main>

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
                <p>COD available nationwide.</p>
            </div>
        </div>
    </footer>

    {{-- Cart drawer --}}
    @include('partials.cart-drawer')

    {{-- Global confirm modal --}}
    <div x-cloak x-show="$store.ui.confirmVisible" x-transition.opacity
        class="fixed inset-0 z-70 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-sm animate-scale-in rounded-2xl border border-white/10 bg-white p-6 shadow-2xl dark:bg-slate-900" @click.outside="$store.ui.cancel()">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-rose-100 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold">Konfirmasi</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400" x-text="$store.ui.confirmMessage"></p>
            <div class="mt-5 flex gap-3">
                <button @click="$store.ui.cancel()" class="btn-outline flex-1">Batal</button>
                <button @click="$store.ui.accept()" class="btn-danger flex-1">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
