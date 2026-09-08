@extends('layouts.app')

@section('title', 'Lupa Password - ALGO NATION')

@section('content')
    <div class="mx-auto flex min-h-[80vh] max-w-7xl items-center justify-center px-4 py-12 sm:px-6 lg:px-8"
         x-data="forgotPassword()" x-cloak>

        {{-- State: Form --}}
        <template x-if="state === 'form'">
            <div class="animate-fade-up w-full max-w-md">
                <div class="mb-8 text-center">
                    <img src="{{ asset('images/logo-an.png') }}" alt="ALGO NATION"
                        class="mx-auto mb-4 h-14 w-14 rounded-2xl object-cover shadow-lg shadow-primary/30"
                        onerror="this.onerror=null; this.src='https://placehold.co/100x100/e9f50b/1b1b18?text=AN';">
                    <h1 class="font-display text-3xl font-extrabold">Lupa Password</h1>
                    <p class="mt-2 text-sm text-slate-500">Masukkan email untuk menerima link reset password.</p>
                </div>

                <div class="glass-strong rounded-2xl p-6 sm:p-8">
                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <label for="email" class="label">Email</label>
                            <input id="email" type="email" name="email" x-model="form.email" required autofocus
                                class="input" :class="{ 'border-rose-400': errors.email }" placeholder="nama@email.com">
                            <template x-if="errors.email">
                                <p class="mt-1 text-xs text-rose-500" x-text="errors.email"></p>
                            </template>
                        </div>

                        <button type="submit"
                            class="btn-primary w-full btn-lg disabled:cursor-not-allowed disabled:opacity-70"
                            :disabled="loading">
                            <span x-show="!loading">Kirim Link Reset Password</span>
                            <span x-show="loading" class="inline-flex items-center justify-center gap-2">
                                <span class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                                Mengirim...
                            </span>
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm text-slate-500">
                        <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline dark:text-primary-soft">Kembali ke Login</a>
                    </p>
                </div>
            </div>
        </template>

        {{-- State: Success --}}
        <template x-if="state === 'success'">
            <div class="animate-fade-up w-full max-w-md">
                <div class="mb-8 text-center">
                    <img src="{{ asset('images/logo-an.png') }}" alt="ALGO NATION"
                        class="mx-auto mb-4 h-14 w-14 rounded-2xl object-cover shadow-lg shadow-primary/30"
                        onerror="this.onerror=null; this.src='https://placehold.co/100x100/e9f50b/1b1b18?text=AN';">
                    <h1 class="font-display text-3xl font-extrabold">Permintaan Berhasil</h1>
                </div>

                <div class="glass-strong rounded-2xl p-6 sm:p-8 text-center">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                        <svg class="h-8 w-8 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Jika email tersebut terdaftar, instruksi reset password telah dikirim. Silakan periksa inbox email kamu.
                    </p>
                    <a href="{{ route('login') }}" class="btn-primary mt-6 w-full btn-lg">Kembali ke Login</a>
                </div>
            </div>
        </template>

        {{-- State: Rate Limited --}}
        <template x-if="state === 'rate_limited'">
            <div class="animate-fade-up w-full max-w-md">
                <div class="mb-8 text-center">
                    <img src="{{ asset('images/logo-an.png') }}" alt="ALGO NATION"
                        class="mx-auto mb-4 h-14 w-14 rounded-2xl object-cover shadow-lg shadow-primary/30"
                        onerror="this.onerror=null; this.src='https://placehold.co/100x100/e9f50b/1b1b18?text=AN';">
                    <h1 class="font-display text-3xl font-extrabold">Terlalu Banyak Permintaan</h1>
                </div>

                <div class="glass-strong rounded-2xl p-6 sm:p-8 text-center">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/30">
                        <svg class="h-8 w-8 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Terlalu banyak permintaan dari akun kamu. Silakan coba lagi dalam
                        <span class="font-semibold text-slate-900 dark:text-white" x-text="countdownText()"></span>.
                    </p>
                    <button type="button"
                        class="btn-primary mt-6 w-full btn-lg disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="countdown > 0" @click="state = 'form'">
                        <span x-show="countdown > 0">Coba lagi dalam <span x-text="countdownText()"></span></span>
                        <span x-show="countdown === 0">Coba Lagi</span>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <script>
        function forgotPassword() {
            return {
                state: 'form',
                loading: false,
                countdown: 0,
                _timer: null,
                form: { email: '{{ old('email') }}' },
                errors: {},

                startCountdown(seconds) {
                    this.state = 'rate_limited';
                    this.errors = {};
                    this.countdown = Math.max(1, Math.ceil(seconds));

                    if (this._timer) {
                        clearInterval(this._timer);
                    }

                    this._timer = setInterval(() => {
                        this.countdown -= 1;

                        if (this.countdown <= 0) {
                            clearInterval(this._timer);
                            this._timer = null;
                            this.state = 'form';
                        }
                    }, 1000);
                },

                countdownText() {
                    const s = Math.max(0, this.countdown);
                    const m = Math.floor(s / 60);
                    const sec = s % 60;

                    if (m > 0) {
                        return `${m} menit ${sec} detik`;
                    }

                    return `${sec} detik`;
                },

                async submit() {
                    this.errors = {};
                    this.loading = true;

                    try {
                        const response = await fetch('{{ route("password.email") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(this.form),
                        });

                        const data = await response.json();

                        if (response.status === 422) {
                            this.errors = data.errors || {};
                            if (data.errors && data.errors.email) {
                                this.errors.email = Array.isArray(data.errors.email)
                                    ? data.errors.email[0]
                                    : data.errors.email;
                            }
                            return;
                        }

                        if (response.status === 429) {
                            this.startCountdown(data.retry_after || 60);
                            return;
                        }

                        if (response.ok) {
                            this.state = 'success';
                            return;
                        }

                        this.errors.email = 'Terjadi kesalahan. Silakan coba lagi.';
                    } catch (e) {
                        this.errors.email = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>
@endsection
