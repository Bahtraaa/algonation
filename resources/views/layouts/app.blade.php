<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ALGO NATION') - Fashion Marketplace</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data class="flex min-h-screen flex-col">
    @include('partials.toasts')

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Main content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

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

    {{-- Floating WhatsApp Customer Service --}}
    <x-whatsapp-button />
</body>
</html>
