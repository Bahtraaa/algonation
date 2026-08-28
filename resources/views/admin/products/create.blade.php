@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('content')
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('admin.products.index') }}" class="btn-ghost btn-sm mb-4">
            ← Kembali ke daftar
        </a>

        <div class="card-flat animate-fade-up p-6">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div x-data="{ preview: null }">
                    <label class="label">Gambar Produk</label>
                    <div class="flex items-center gap-4">
                        <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 dark:border-white/15 dark:bg-white/5">
                            <template x-if="!preview">
                                <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </template>
                            <template x-if="preview">
                                <img :src="preview" alt="" class="h-full w-full object-cover">
                            </template>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" accept="image/*" @change="preview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : null"
                                class="block w-full text-sm text-slate-500 file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-ink hover:file:bg-primary-dark">
                            <p class="mt-2 text-xs text-slate-400">JPG, PNG, WEBP. Maksimal 2MB.</p>
                            @error('image')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label class="label">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input @error('name') border-rose-400 @enderror" placeholder="Contoh: Lemari Pakaian Minimalis 3 Pintu">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Kategori</label>
                    <select name="category" required class="input @error('category') border-rose-400 @enderror">
                        <option value="">Pilih kategori...</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Deskripsi</label>
                    <textarea name="description" rows="4" class="input" placeholder="Deskripsi detail produk...">{{ old('description') }}</textarea>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', 0) }}" min="0" step="1000" required class="input">
                    </div>
                    <div>
                        <label class="label">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required class="input">
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

