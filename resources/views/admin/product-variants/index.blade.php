@extends('layouts.admin')

@section('title', 'Produk Variant')
@section('page-title', 'Produk Variant')

@section('content')
    {{-- Top bar: filter + tambah --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-1 flex-col gap-3 sm:flex-row">
            <form action="{{ route('admin.product-variants.index') }}" method="GET" class="relative flex-1 sm:max-w-xs">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari variant / SKU / produk..."
                    class="input pl-10">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                @if (request('product_id'))
                    <input type="hidden" name="product_id" value="{{ request('product_id') }}">
                @endif
            </form>
            <form action="{{ route('admin.product-variants.index') }}" method="GET">
                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <select name="product_id" onchange="this.form.submit()" class="input sm:w-56">
                    <option value="all" {{ !request('product_id') || request('product_id') === 'all' ? 'selected' : '' }}>Semua Produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ (string) request('product_id') === (string) $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <a href="{{ route('admin.product-variants.create', request('product_id') && request('product_id') !== 'all' ? ['product_id' => request('product_id')] : []) }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            + Tambah Variant
        </a>
    </div>

    {{-- Table sesuai spesifikasi: Produk | Warna | Ukuran | SKU | Stok | Harga | Aksi --}}
    <div class="table-wrap animate-fade-up">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Produk</th>
                    <th>Warna</th>
                    <th>Ukuran</th>
                    <th>SKU</th>
                    <th class="text-right">Stok</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($variants as $variant)
                    <tr>
                        <td>
                            <img src="{{ $variant->effective_image_url }}" alt="Gambar {{ $variant->display_name }}" loading="lazy"
                                class="h-14 w-14 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-white/10"
                                title="{{ $variant->image ? 'Gambar variant' : 'Gambar produk (fallback)' }}">
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                @if ($variant->product)
                                    <img src="{{ $variant->product->image_url }}" alt="{{ $variant->product->name }}" class="h-11 w-11 shrink-0 rounded-xl object-cover">
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold">{{ $variant->product->name }}</p>
                                        <p class="truncate text-xs text-slate-500">Rp {{ number_format($variant->product->price, 0, ',', '.') }} (dasar)</p>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">Produk dihapus</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $variant->color ?? '—' }}</td>
                        <td>{{ $variant->size ?? '—' }}</td>
                        <td class="font-mono text-xs">{{ $variant->sku ?? '—' }}</td>
                        <td class="text-right">
                            @if ($variant->stock <= 0)
                                <span class="badge-danger">Habis</span>
                            @else
                                <span class="badge-success">{{ $variant->stock }}</span>
                            @endif
                        </td>
                        <td class="text-right font-semibold">Rp {{ number_format($variant->price ?? $variant->product?->price ?? 0, 0, ',', '.') }}</td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.product-variants.edit', $variant) }}" class="btn-ghost btn-sm" title="Edit">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.product-variants.destroy', $variant) }}"
                                    id="delete-variant-form-{{ $variant->id }}"
                                    onsubmit="event.preventDefault(); window.confirmAction('Hapus variant {{ $variant->display_name }}?', () => { document.getElementById('delete-variant-form-{{ $variant->id }}').submit(); })">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-ghost btn-sm text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10" title="Hapus">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center">
                            <p class="font-semibold">Belum ada variant.</p>
                            <p class="mt-1 text-sm text-slate-500">Contoh: T-Shirt Basic — Hitam — M — Stok 15.</p>
                            <a href="{{ route('admin.product-variants.create') }}" class="btn-primary btn-sm mt-4">+ Tambah Variant</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($variants->hasPages())
        <div class="mt-6">
            {{ $variants->links() }}
        </div>
    @endif
@endsection
