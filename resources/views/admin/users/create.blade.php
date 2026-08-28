@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')

@section('content')
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('admin.users.index') }}" class="btn-ghost btn-sm mb-4">
            ← Kembali ke daftar pengguna
        </a>

        <div class="card-flat animate-fade-up p-6">
            <div class="mb-6 flex items-center gap-3">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/20 text-ink">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </span>
                <div>
                    <h2 class="font-display text-lg font-bold">Buat Akun Baru</h2>
                    <p class="text-sm text-slate-500">Tambahkan akun admin atau customer baru.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input @error('name') border-rose-400 @enderror" placeholder="Contoh: Siti Aminah">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="input @error('email') border-rose-400 @enderror" placeholder="nama@email.com">
                        @error('email')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="label">Username <span class="text-xs font-normal text-slate-400">(opsional)</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" class="input @error('username') border-rose-400 @enderror" placeholder="username">
                        @error('username')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">Password</label>
                        <input type="password" name="password" required class="input @error('password') border-rose-400 @enderror" placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required class="input" placeholder="Ulangi password">
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">Role</label>
                        <select name="role" class="input">
                            <option value="user" {{ old('role') === 'user' || !old('role') ? 'selected' : '' }}>Customer</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Status</label>
                        <select name="status" class="input">
                            <option value="active" {{ old('status') === 'active' || !old('status') ? 'selected' : '' }}>Aktif</option>
                            <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5 dark:border-white/10">
                    <a href="{{ route('admin.users.index') }}" class="btn-outline">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Buat Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

