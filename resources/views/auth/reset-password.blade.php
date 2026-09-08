@extends('layouts.app')

@section('title', 'Reset Password - ALGO NATION')

@section('content')
    <div class="mx-auto flex min-h-[80vh] max-w-7xl items-center justify-center px-4 py-12 sm:px-6 lg:px-8">

        {{-- State: Form --}}
        @if($state === 'form')
            <div class="animate-fade-up w-full max-w-md" x-data="{ loading: false }">
                <div class="mb-8 text-center">
                    <img src="{{ asset('images/logo-an.png') }}" alt="ALGO NATION"
                        class="mx-auto mb-4 h-14 w-14 rounded-2xl object-cover shadow-lg shadow-primary/30"
                        onerror="this.onerror=null; this.src='https://placehold.co/100x100/e9f50b/1b1b18?text=AN';">
                    <h1 class="font-display text-3xl font-extrabold">Reset Password</h1>
                    <p class="mt-2 text-sm text-slate-500">Masukkan password baru untuk akun kamu.</p>
                </div>

                <div class="glass-strong rounded-2xl p-6 sm:p-8">
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5" @submit="loading = true">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div>
                            <label for="email-display" class="label">Email</label>
                            <input id="email-display" type="email" value="{{ $email }}" readonly
                                class="input cursor-not-allowed bg-slate-50 dark:bg-slate-800">
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password" class="label">Password Baru</label>
                            <div class="relative">
                                <input id="password" :type="show ? 'text' : 'password'" name="password" required
                                    class="input pr-12 @error('password') border-rose-400 @enderror" placeholder="Min. 8 karakter">
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

                        <div x-data="{ show: false }">
                            <label for="password_confirmation" class="label">Konfirmasi Password</label>
                            <div class="relative">
                                <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required
                                    class="input pr-12 @error('password_confirmation') border-rose-400 @enderror" placeholder="Ulangi password baru">
                                <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="btn-primary w-full btn-lg disabled:cursor-not-allowed disabled:opacity-70"
                            :disabled="loading">
                            <span x-show="!loading">Reset Password</span>
                            <span x-show="loading" class="inline-flex items-center justify-center gap-2">
                                <span class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                                Memproses...
                            </span>
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm text-slate-500">
                        <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline dark:text-primary-soft">Kembali ke Login</a>
                    </p>
                </div>
            </div>
        @endif

        {{-- State: Expired --}}
        @if($state === 'expired')
            <div class="animate-fade-up w-full max-w-md">
                <div class="mb-8 text-center">
                    <img src="{{ asset('images/logo-an.png') }}" alt="ALGO NATION"
                        class="mx-auto mb-4 h-14 w-14 rounded-2xl object-cover shadow-lg shadow-primary/30"
                        onerror="this.onerror=null; this.src='https://placehold.co/100x100/e9f50b/1b1b18?text=AN';">
                    <h1 class="font-display text-3xl font-extrabold">Link Reset Kedaluwarsa</h1>
                </div>

                <div class="glass-strong rounded-2xl p-6 sm:p-8 text-center">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/30">
                        <svg class="h-8 w-8 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Link reset password ini sudah tidak berlaku. Silakan meminta link reset password baru.
                    </p>
                    <a href="{{ route('password.request') }}" class="btn-primary mt-6 w-full btn-lg">Minta Link Baru</a>
                </div>
            </div>
        @endif

        {{-- State: Invalid --}}
        @if($state === 'invalid')
            <div class="animate-fade-up w-full max-w-md">
                <div class="mb-8 text-center">
                    <img src="{{ asset('images/logo-an.png') }}" alt="ALGO NATION"
                        class="mx-auto mb-4 h-14 w-14 rounded-2xl object-cover shadow-lg shadow-primary/30"
                        onerror="this.onerror=null; this.src='https://placehold.co/100x100/e9f50b/1b1b18?text=AN';">
                    <h1 class="font-display text-3xl font-extrabold">Link Tidak Valid</h1>
                </div>

                <div class="glass-strong rounded-2xl p-6 sm:p-8 text-center">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-rose-100 dark:bg-rose-900/30">
                        <svg class="h-8 w-8 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Link reset password tidak valid atau sudah tidak dapat digunakan. Silakan meminta link reset password baru.
                    </p>
                    <a href="{{ route('password.request') }}" class="btn-primary mt-6 w-full btn-lg">Minta Link Baru</a>
                </div>
            </div>
        @endif
    </div>
@endsection
