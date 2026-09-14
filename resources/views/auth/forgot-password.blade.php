@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
    <div class="mx-auto max-w-md px-4 py-16 sm:px-6 lg:px-8">
        <div class="glass-strong rounded-2xl p-6 sm:p-8">
            <div class="mb-6 text-center">
                <h1 class="font-display text-3xl font-extrabold text-slate-900 dark:text-white">Lupa Password</h1>
                <p class="mt-2 text-sm text-slate-500">Masukkan email akun Anda untuk menerima link reset password.</p>
            </div>

            @if (session('status'))
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="input @error('email') border-rose-400 @enderror" placeholder="nama@email.com">
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary w-full">Kirim Link Reset Password</button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-primary hover:underline">Kembali ke Login</a>
                </div>
            </form>
        </div>
    </div>
@endsection
