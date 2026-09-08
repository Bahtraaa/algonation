@extends('layouts.admin')

@section('title', 'Produk Unggulan')
@section('page-title', 'Produk Unggulan')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">
        {{-- Add featured product --}}
        <div class="card-flat p-6">
            <h2 class="font-display text-lg font-bold">Tambah Produk Unggulan</h2>
            <p class="mt-1 text-xs text-slate-500">
                Pilih produk dari katalog yang sudah ada. Produk unggulan hanya mereferensikan produk asli —
                tidak ada salinan data (nama, harga, stok, gambar, deskripsi) yang dibuat.
            </p>
            <form method="POST" action="{{ route('admin.featured-products.store') }}" class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @csrf
                <select name="product_id" required class="input sm:col-span-2">
                    <option value="">Pilih produk...</option>
                    @forelse ($available as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} — Rp {{ number_format($product->display_price, 0, ',', '.') }}</option>
                    @empty
                        <option value="" disabled>Semua produk sudah menjadi unggulan</option>
                    @endforelse
                </select>
                <button class="btn-primary self-end lg:col-span-2 sm:col-span-2 sm:justify-self-end">Tambahkan ke Produk Unggulan</button>
            </form>
            @if ($errors->any())<div class="mt-4 text-sm text-rose-600">{{ $errors->first() }}</div>@endif
            @if (session('error'))<div class="mt-4 text-sm text-rose-600">{{ session('error') }}</div>@endif
        </div>

        {{-- Featured list --}}
        <div class="table-wrap">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Flash Sale</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($featured as $entry)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $entry->product->image_url }}" alt="" class="h-11 w-11 shrink-0 rounded-xl object-cover">
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold">{{ $entry->product->name }}</p>
                                        <p class="truncate text-xs text-slate-500">{{ $entry->product->category }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($entry->product->has_active_flash_sale)
                                    <del class="text-xs text-slate-400">Rp {{ number_format($entry->product->regular_price, 0, ',', '.') }}</del><br>
                                    <b>Rp {{ number_format($entry->product->active_price, 0, ',', '.') }}</b>
                                @else
                                    <b>Rp {{ number_format($entry->product->regular_price, 0, ',', '.') }}</b>
                                @endif
                            </td>
                            <td>
                                @if ($entry->product->has_active_flash_sale)
                                    <span class="badge-warning">FLASH SALE</span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <form method="POST" action="{{ route('admin.featured-products.destroy', $entry) }}"
                                        onsubmit="return confirm('Hapus {{ $entry->product->name }} dari produk unggulan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost btn-sm text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10" title="Hapus">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-16 text-center">
                                <p class="font-semibold">Belum ada produk unggulan.</p>
                                <p class="mt-1 text-sm text-slate-500">Pilih produk dari katalog untuk ditampilkan sebagai unggulan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection