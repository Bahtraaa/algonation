@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('content')
    <div class="mx-auto max-w-2xl" x-data="{ preview: null }">
        <a href="{{ route('admin.products.index') }}" class="btn-ghost btn-sm mb-4">
            ← Kembali ke daftar produk
        </a>

        <div class="card-flat animate-fade-up p-6">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-5">
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
                    <label class="label">Nama Produk <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input" placeholder="Contoh: T-Shirt Basic">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">Kategori <span class="text-rose-500">*</span></label>
                        <select name="category" required class="input">
                            <option value="">Pilih kategori...</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Status</label>
                        <select name="status" class="input">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="label">Deskripsi</label>
                    <textarea name="description" rows="4" class="input" placeholder="Deskripsi produk...">{{ old('description') }}</textarea>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">Harga Dasar (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price', 0) }}" min="0" step="1000" required class="input">
                    </div>
                    <div>
                        <label class="label">Stok <span class="text-rose-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required class="input">
                    </div>
                </div>

                <div>
                    <p class="label">Berat & Dimensi <span class="text-slate-400">(dipakai untuk menghitung ongkir)</span></p>
                    <div class="mt-1 grid gap-3 sm:grid-cols-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-500">Berat (gram)</label>
                            <input type="number" name="weight" value="{{ old('weight', 300) }}" min="0" step="1" class="input mt-1" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500">Panjang (cm)</label>
                            <input type="number" name="length" value="{{ old('length', 40) }}" min="0" step="0.1" class="input mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500">Lebar (cm)</label>
                            <input type="number" name="width" value="{{ old('width', 30) }}" min="0" step="0.1" class="input mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500">Tinggi (cm)</label>
                            <input type="number" name="height" value="{{ old('height', 20) }}" min="0" step="0.1" class="input mt-1">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5 dark:border-white/10">
                    <a href="{{ route('admin.products.index') }}" class="btn-outline">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
@endsection
