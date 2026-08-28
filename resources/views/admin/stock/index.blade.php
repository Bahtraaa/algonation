@extends('layouts.admin')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')

@section('content')
    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="card-flat p-5">
            <p class="text-xs text-slate-500">Total Produk</p>
            <p class="mt-1 font-display text-2xl font-extrabold">{{ $stats['total'] }}</p>
        </div>
        <div class="card-flat p-5">
            <p class="text-xs text-slate-500">Total Unit Stok</p>
            <p class="mt-1 font-display text-2xl font-extrabold">{{ $stats['totalStock'] }}</p>
        </div>
        <div class="card-flat p-5">
            <p class="text-xs text-slate-500">Stok Menipis</p>
            <p class="mt-1 font-display text-2xl font-extrabold text-primary">{{ $stats['low'] }}</p>
        </div>
        <div class="card-flat p-5">
            <p class="text-xs text-slate-500">Stok Habis</p>
            <p class="mt-1 font-display text-2xl font-extrabold text-rose-600 dark:text-rose-400">{{ $stats['out'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
        <form action="{{ route('admin.stock.index') }}" method="GET" class="relative flex-1 sm:max-w-xs">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                class="input pl-10">
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </form>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.stock.index') }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ !request('filter') ? 'bg-primary text-ink' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-white/5 dark:text-slate-300' }}">
                Semua
            </a>
            <a href="{{ route('admin.stock.index', ['filter' => 'low']) }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ request('filter') === 'low' ? 'bg-primary text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-white/5 dark:text-slate-300' }}">
                ⚠️ Stok Menipis
            </a>
            <a href="{{ route('admin.stock.index', ['filter' => 'out']) }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ request('filter') === 'out' ? 'bg-rose-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-white/5 dark:text-slate-300' }}">
                ✖️ Stok Habis
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-wrap mt-6 animate-fade-up">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Stok Dasar</th>
                    <th>Varian</th>
                    <th>Total Stok</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->image_url }}" alt="" class="h-10 w-10 shrink-0 rounded-lg object-cover">
                                <span class="font-semibold">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td><span class="badge {{ $product->category_class }}">{{ $product->category }}</span></td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            @if ($product->variants->isNotEmpty())
                                <div class="space-y-0.5">
                                    @foreach ($product->variants as $variant)
                                        <span class="block text-xs text-slate-600 dark:text-slate-400">
                                            {{ $variant->name }}: {{ $variant->stock }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="font-bold">{{ $product->total_stock }}</td>
                        <td>
                            @if ($product->is_out_of_stock)
                                <span class="badge-danger">Stok Habis</span>
                            @elseif ($product->is_low_stock)
                                <span class="badge-warning">Menipis</span>
                            @else
                                <span class="badge-success">Aman</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex justify-end">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn-outline btn-sm">Tambah Stok</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center">
                            <p class="font-semibold">Tidak ada data stok.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($products->hasPages())
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif
@endsection

