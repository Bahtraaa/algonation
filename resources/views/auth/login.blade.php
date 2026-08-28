@extends('layouts.app')

@section('title', 'Masuk — ALGO NATION')

@section('content')
    <div class="mx-auto grid min-h-[80vh] max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-2 lg:px-8">
        {{-- Form --}}
        <div class="animate-fade-up">
            <div class="mx-auto w-full max-w-md">
                <div class="mb-8 text-center lg:text-left">
                    <img src="{{ asset('images/logo-an.png') }}" alt="ALGO NATION" class="mx-auto mb-4 h-14 w-14 rounded-2xl object-cover shadow-lg shadow-primary/30 lg:mx-0" onerror="this.onerror=null; this.src='https://placehold.co/100x100/e9f50b/1b1b18?text=AN';">
                    <h1 class="font-display text-3xl font-extrabold">Selamat Datang Kembali!</h1>
                    <p class="mt-2 text-sm text-slate-500">Masuk untuk melanjutkan belanja fashion yang membuatmu tampil percaya diri.</p>
                </div>

                <div class="glass-strong rounded-2xl p-6 sm:p-8">
                    <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="label">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="input @error('email') border-rose-400 @enderror" placeholder="nama@email.com">
                            @error('email')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password" class="label">Password</label>
                            <div class="relative">
                                <input id="password" :type="show ? 'text' : 'password'" name="password" required
                                    class="input pr-12 @error('password') border-rose-400 @enderror" placeholder="••••••••">
                                <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary">
                                Ingat saya
                            </label>
                        </div>

                        <button type="submit" class="btn-primary w-full btn-lg">Masuk</button>
                    </form>

                    <p class="mt-6 text-center text-sm text-slate-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-semibold text-primary hover:underline dark:text-primary-soft">Daftar sekarang</a>
                    </p>
                </div>
            </div>
        </div>

        {{-- Banner --}}
        <div class="relative hidden lg:block">
            <div class="animate-float">
                    <div class="relative mx-auto max-w-md">
                    <div class="absolute -inset-6 rounded-[2.5rem] bg-linear-to-br from-primary via-[#6b4423] to-[#4a2f1b] opacity-30 blur-3xl"></div>
                    <div class="glass-strong relative overflow-hidden rounded-4xl p-8">
                        <span class="badge-primary mb-4">Bergabung dengan ribuan pelanggan</span>
                        <h2 class="font-display text-2xl font-extrabold leading-snug">Masuk dan temukan gaya fashion terbaikmu</h2>
                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">Nikmati kemudahan belanja online dengan pembayaran di tempat. Aman, nyaman, dan terpercaya.</p>
                        <div class="mt-6 space-y-3">
                            <div class="flex items-center gap-3 rounded-xl bg-white/60 p-3 dark:bg-white/5">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary text-ink">✓</span>
                                <span class="text-sm font-medium">Produk asli & berkualitas</span>
                            </div>
                            <div class="flex items-center gap-3 rounded-xl bg-white/60 p-3 dark:bg-white/5">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary text-ink">✓</span>
                                <span class="text-sm font-medium">Gratis ongkir min. belanja Rp 500rb</span>
                            </div>
                            <div class="flex items-center gap-3 rounded-xl bg-white/60 p-3 dark:bg-white/5">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary text-ink">✓</span>
                                <span class="text-sm font-medium">Bayar di tempat (COD)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

