@extends('layouts.app')

@section('title', 'Keranjang Kosong - ALGO NATION')

@section('content')
    <div class="mx-auto flex min-h-[60vh] max-w-7xl flex-col items-center justify-center px-4 py-16 text-center">
        <div class="animate-float mb-6 flex h-28 w-28 items-center justify-center rounded-full bg-slate-100 dark:bg-white/5">
            <svg class="h-14 w-14 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <h1 class="font-display text-2xl font-extrabold">Keranjang Anda Kosong</h1>
        <p class="mt-2 max-w-md text-sm text-slate-500">Anda perlu menambahkan produk ke keranjang sebelum checkout. Yuk mulai belanja!</p>
        <a href="{{ route('shop') }}" class="btn-primary btn-lg mt-8">Lihat Produk</a>
    </div>
@endsection

