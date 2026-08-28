@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('page-title', 'Produk')

@section('content')
    {{-- Top bar --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-1 flex-col gap-3 sm:flex-row">
            <form action="{{ route('admin.products.index') }}" method="GET" class="relative flex-1 sm:max-w-xs">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                    class="input pl-10">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
            <form action="{{ route('admin.products.index') }}" method="GET">
                <select name="category" onchange="this.form.submit()" class="input sm:w-44">
                    <option value="all" {{ request('category') === 'all' || !request('category') ? 'selected' : '' }}>Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="flex gap-2">
            <button type="button" @click="$store.ui.openProductModal()" class="btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Tambah Produk
            </button>
        </div>
    </div>

    {{-- Quick Add Product Modal --}}
    <div x-data="{ preview: null }"
        x-show="$store.ui.productModal"
        x-transition.opacity
        class="fixed inset-0 z-60 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm"
        style="display: none">
        <div class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-2xl border border-white/10 bg-white shadow-2xl dark:bg-slate-900"
            @click.outside="$store.ui.closeProductModal()">
            <div class="sticky top-0 flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-white/10">
                <h3 class="font-display font-bold">Tambah Produk Baru</h3>
                <button @click="$store.ui.closeProductModal()" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-white/5">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-5 p-6">
                @csrf

                <div>
                    <label class="label">Gambar Produk</label>
                    <div class="flex items-center gap-4">
                        <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 dark:border-white/15 dark:bg-white/5">
                            <template x-if="!preview">
                                <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </template>
                            <img x-show="preview" :src="preview" alt="" class="h-full w-full object-cover">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])"
                                class="block w-full text-sm text-slate-500 file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-ink hover:file:bg-primary-dark">
                            <p class="mt-2 text-xs text-slate-400">JPG, PNG, WEBP. Maksimal 2MB.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="label">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input" placeholder="Contoh: Lemari Pakaian Minimalis">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">Kategori</label>
                        <select name="category" required class="input">
                            <option value="">Pilih kategori...</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', 0) }}" min="0" step="1000" required class="input">
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required class="input">
                    </div>
                    <div>
                        <label class="label">Deskripsi</label>
                        <input type="text" name="description" value="{{ old('description') }}" class="input" placeholder="Deskripsi singkat...">
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5 dark:border-white/10">
                    <button type="button" @click="$store.ui.closeProductModal()" class="btn-outline">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-wrap animate-fade-up">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Varian</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-11 w-11 shrink-0 rounded-xl object-cover">
                                <div class="min-w-0">
                                    <p class="truncate font-semibold">{{ $product->name }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ Str::limit($product->description, 40) }}</p>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge {{ $product->category_class }}">{{ $product->category }}</span></td>
                        <td>
                            @if ($product->variants->isNotEmpty())
                                <span class="text-sm">{{ $product->variants->count() }} varian</span>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                        <td>
                            @if ($product->is_out_of_stock)
                                <span class="badge-danger">Habis</span>
                            @elseif ($product->is_low_stock)
                                <span class="badge-warning">{{ $product->total_stock }} (menipis)</span>
                            @else
                                <span class="badge-success">{{ $product->total_stock }}</span>
                            @endif
                        </td>
                        <td class="font-semibold">Rp {{ number_format($product->display_price, 0, ',', '.') }}</td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn-ghost btn-sm" title="Edit">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                    onsubmit="event.preventDefault(); window.confirmAction('Hapus produk {{ $product->name }}?', () => { document.getElementById('delete-form-{{ $product->id }}').submit(); })"
                                    id="delete-form-{{ $product->id }}">
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
                        <td colspan="6" class="py-16 text-center">
                            <p class="font-semibold">Belum ada produk.</p>
                            <p class="mt-1 text-sm text-slate-500">Tambahkan produk pertama Anda.</p>
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

