@extends('layouts.app')

@section('title', 'Profil Saya — ALGO NATION')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="animate-fade-up">
            <p class="text-xs font-bold uppercase tracking-widest text-[#5c3d25] dark:text-primary-soft">Akun</p>
            <h1 class="mt-1 font-display text-3xl font-extrabold">Profil Saya</h1>
        </div>

        {{-- Stats --}}
        <div class="mt-8 grid grid-cols-3 gap-4">
            <div class="card-flat p-5 text-center">
                <p class="font-display text-2xl font-extrabold text-primary dark:text-primary-soft">{{ $stats['orders'] }}</p>
                <p class="mt-1 text-xs text-slate-500">Total Pesanan</p>
            </div>
            <div class="card-flat p-5 text-center">
                <p class="font-display text-2xl font-extrabold text-primary dark:text-primary-soft">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-500">Total Belanja</p>
            </div>
            <div class="card-flat p-5 text-center">
                <p class="font-display text-2xl font-extrabold text-primary dark:text-primary-soft">{{ $stats['pending'] }}</p>
                <p class="mt-1 text-xs text-slate-500">Menunggu</p>
            </div>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-2">
            {{-- Profile info --}}
            <div class="card-flat p-6 animate-fade-up">
                <h2 class="mb-5 font-display text-lg font-bold">Informasi Profil</h2>
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="input">
                    </div>
                    <div>
                        <label class="label">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="input">
                    </div>
                    <div>
                        <label class="label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="input">
                    </div>
                    <button type="submit" class="btn-primary w-full">Simpan Perubahan</button>
                </form>
            </div>

            {{-- Password --}}
            <div class="card-flat p-6 animate-fade-up delay-150ms">
                <h2 class="mb-5 font-display text-lg font-bold">Ubah Password</h2>
                <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="label">Password Saat Ini</label>
                        <input type="password" name="current_password" required class="input">
                    </div>
                    <div>
                        <label class="label">Password Baru</label>
                        <input type="password" name="password" required class="input" placeholder="Min. 8 karakter">
                    </div>
                    <div>
                        <label class="label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required class="input">
                    </div>
                    <button type="submit" class="btn-dark w-full">Perbarui Password</button>
                </form>
            </div>
        </div>

        {{-- Recent orders --}}
        <div class="card-flat mt-8 animate-fade-up">
            <div class="flex items-center justify-between border-b border-slate-100 p-5 dark:border-white/10">
                <h2 class="font-display text-lg font-bold">Pesanan Terakhir</h2>
                <a href="{{ route('orders') }}" class="btn-ghost btn-sm">Lihat Semua</a>
            </div>
            @forelse ($recentOrders as $order)
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-50 px-5 py-4 last:border-0 dark:border-white/5">
                    <div>
                        <p class="text-sm font-semibold">{{ $order->invoice_number }}</p>
                        <p class="text-xs text-slate-500">{{ $order->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="badge {{ $order->status_class }}">{{ ucfirst($order->status) }}</span>
                        <span class="text-sm font-bold">Rp {{ number_format($order->total_price + $order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <p class="px-5 py-8 text-center text-sm text-slate-500">Belum ada pesanan.</p>
            @endforelse
        </div>
    </div>
@endsection

