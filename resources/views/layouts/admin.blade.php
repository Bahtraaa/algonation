<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin ALGO NATION</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body x-data="{ sidebarOpen: false }" class="min-h-screen bg-paper dark:bg-slate-950">
    @include('partials.toasts')

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-slate-200 bg-white/80 backdrop-blur-2xl transition-transform duration-300 dark:border-white/10 dark:bg-slate-900/80 lg:static lg:translate-x-0 lg:bg-white/60 dark:lg:bg-slate-900/60">

            <div class="flex h-16 items-center justify-between border-b border-slate-100 px-5 dark:border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo-an.png') }}" alt="ALGO NATION" class="h-9 w-9 rounded-xl object-cover shadow-lg shadow-primary/30" onerror="this.onerror=null; this.src='https://placehold.co/100x100/e9f50b/1b1b18?text=AN';">
                    <span>
                        <span class="block font-display text-sm font-bold leading-tight">ALGO NATION</span>
                        <span class="block text-[10px] font-medium uppercase tracking-widest text-slate-500">Admin Panel</span>
                    </span>
                </a>
                <button @click="sidebarOpen = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto p-4">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard', 'admin.dashboard.index') ? 'active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </a>

                <p class="mt-6 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Kelola</p>

                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Produk
                </a>

                <a href="{{ route('admin.stock.index') }}" class="nav-link {{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    Stok
                </a>

                <a href="{{ route('admin.sales.index') }}" class="nav-link {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Penjualan
                </a>

                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Pengguna
                </a>
            </nav>

            <div class="border-t border-slate-100 p-4 dark:border-white/10">
                <div class="mb-3 flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary font-bold text-ink">{{ auth()->user()->initial }}</span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-slate-500">Admin</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('home') }}" class="btn-ghost btn-sm flex-1">Toko</a>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-outline btn-sm w-full text-rose-600 dark:text-rose-400">Keluar</button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Backdrop for mobile --}}
        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"></div>

        {{-- Main --}}
        <div class="flex min-h-screen flex-1 flex-col">
            {{-- Topbar --}}
            <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/70 backdrop-blur-xl dark:border-white/10 dark:bg-slate-950/70">
                <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = true" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 lg:hidden dark:border-white/10">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <h1 class="font-display text-base font-bold sm:text-lg">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="$store.theme.toggle()"
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 dark:border-white/10 dark:text-slate-300">
                            <svg x-show="!$store.theme.dark" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                            <svg x-show="$store.theme.dark" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </button>
                        <span class="hidden rounded-full bg-primary/15 px-3 py-1 text-xs font-semibold text-[#5a3719] sm:block dark:text-primary-soft">Admin</span>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @include('partials.cart-drawer')

    {{-- Global confirm modal --}}
    <div x-cloak x-show="$store.ui.confirmVisible" x-transition.opacity
        style="display: none;"
        class="fixed inset-0 z-70 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-sm animate-scale-in rounded-2xl border border-white/10 bg-white p-6 shadow-2xl dark:bg-slate-900" @click.outside="$store.ui.cancel()">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-rose-100 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
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

