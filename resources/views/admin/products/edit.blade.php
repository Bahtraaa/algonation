@extends('layouts.admin')

@section('title', 'Edit Produk - ' . $product->name)
@section('page-title', 'Edit Produk')

@section('content')
    <div class="mx-auto max-w-4xl">
        <a href="{{ route('admin.products.index') }}" class="btn-ghost btn-sm mb-4">
            ← Kembali ke daftar
        </a>

        {{-- Edit form --}}
        <div class="card-flat animate-fade-up p-6">
            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div x-data="{ preview: null }">
                    <label class="label">Gambar Produk</label>
                    <div class="flex items-center gap-4">
                        <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 dark:border-white/15 dark:bg-white/5">
                            <template x-if="!preview">
                                <img src="{{ $product->image_url }}" alt="" class="h-full w-full object-cover">
                            </template>
                            <template x-if="preview">
                                <img :src="preview" alt="" class="h-full w-full object-cover">
                            </template>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" accept="image/*" @change="preview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : null"
                                class="block w-full text-sm text-slate-500 file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-ink hover:file:bg-primary-dark">
                            <p class="mt-2 text-xs text-slate-400">Kosongkan jika tidak mengganti gambar.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="label">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="input">
                </div>

                <div>
                    <label class="label">Kategori</label>
                    <select name="category" required class="input">
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ old('category', $product->category) === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="label">Status</label>
                    <select name="status" class="input">
                        <option value="active" {{ old('status', $product->status ?? 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $product->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div>
                    <label class="label">Deskripsi</label>
                    <textarea name="description" rows="4" class="input">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="1000" required class="input">
                    </div>
                    <div>
                        <label class="label">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required class="input">
                    </div>
                </div>

                <div>
                    <p class="label">Berat & Dimensi <span class="text-slate-400">(dipakai untuk menghitung ongkir)</span></p>
                    <div class="mt-1 grid gap-3 sm:grid-cols-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-500">Berat (gram)</label>
                            <input type="number" name="weight" value="{{ old('weight', $product->weight ?? 300) }}" min="0" step="1" class="input mt-1" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500">Panjang (cm)</label>
                            <input type="number" name="length" value="{{ old('length', $product->length ?? 40) }}" min="0" step="0.1" class="input mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500">Lebar (cm)</label>
                            <input type="number" name="width" value="{{ old('width', $product->width ?? 30) }}" min="0" step="0.1" class="input mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500">Tinggi (cm)</label>
                            <input type="number" name="height" value="{{ old('height', $product->height ?? 20) }}" min="0" step="0.1" class="input mt-1">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5 dark:border-white/10">
                    <a href="{{ route('admin.products.index') }}" class="btn-outline">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        {{-- Add stock --}}
        <div class="card-flat mt-6 animate-fade-up p-6">
            <h2 class="font-display font-bold">Tambah Stok</h2>
            <form method="POST" action="{{ route('admin.products.stock', $product) }}" class="mt-4 flex items-end gap-3">
                @csrf
                <div class="flex-1">
                    <label class="label">Jumlah Stok</label>
                    <input type="number" name="quantity" min="1" required class="input" placeholder="Contoh: 20">
                </div>
                <button type="submit" class="btn-dark">Tambah Stok</button>
            </form>
        </div>

    </div>
@endsection

