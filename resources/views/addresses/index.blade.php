@extends('layouts.app')

@section('title', 'Alamat Saya - ALGO NATION')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="animate-fade-up flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-primary dark:text-primary-soft">Akun</p>
                <h1 class="mt-1 font-display text-3xl font-extrabold">Alamat Saya</h1>
                <p class="mt-1 text-sm text-slate-500">Simpan sekali, otomatis dipakai saat checkout.</p>
            </div>
            <a href="{{ route('addresses.create') }}" class="btn-primary btn-sm">+ Tambah Alamat</a>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-2">
            @forelse ($addresses as $address)
                <div class="card-flat animate-fade-up p-6" style="animation-delay: {{ $loop->index * 60 }}ms">
                    <div class="flex items-start justify-between gap-3">
                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold dark:bg-white/10">{{ $address->label }}</span>
                        @if ($address->is_default)
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300">Alamat Utama</span>
                        @endif
                    </div>

                    <p class="mt-3 text-sm font-bold">{{ $address->recipient_name }}</p>
                    <p class="text-sm text-slate-500">{{ $address->phone }}</p>
                    <p class="mt-2 whitespace-pre-line text-sm text-slate-600 dark:text-slate-300">{{ $address->full_address }}</p>
                    <p class="mt-2 text-xs text-slate-400">{{ $address->district }}, {{ $address->city }} — {{ $address->province }}, {{ $address->postal_code }}</p>

                    <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4 dark:border-white/10">
                        <a href="{{ route('addresses.edit', $address) }}" class="btn-outline btn-sm">Edit</a>
                        <form method="POST" action="{{ route('addresses.destroy', $address) }}" onsubmit="event.preventDefault(); window.confirmAction('Hapus alamat <strong>{{ addslashes($address->label) }}</strong>?', () => this.submit());">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm">Hapus</button>
                        </form>
                        @unless ($address->is_default)
                            <form method="POST" action="{{ route('addresses.default', $address) }}" class="ml-auto">
                                @csrf
                                <button type="submit" class="btn-ghost btn-sm">Jadikan Utama</button>
                            </form>
                        @endunless
                    </div>
                </div>
            @empty
                <div class="card-flat py-16 text-center md:col-span-2">
                    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 dark:bg-white/5">
                        <svg class="h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold">Belum ada alamat tersimpan</h3>
                    <p class="mt-1 text-sm text-slate-500">Tambahkan alamat pengiriman sekali, lalu checkout tanpa mengetik ulang.</p>
                    <a href="{{ route('addresses.create') }}" class="btn-primary mt-6">+ Tambah Alamat</a>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            <a href="{{ route('profile') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400">← Kembali ke Profil</a>
        </div>
    </div>
@endsection
