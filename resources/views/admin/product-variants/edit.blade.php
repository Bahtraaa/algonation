@extends('layouts.admin')

@section('title', 'Edit Variant - ' . $variant->display_name)
@section('page-title', 'Edit Variant')

@section('content')
    <div class="mx-auto max-w-2xl" x-data="{ preview: null }">
        <a href="{{ route('admin.product-variants.index') }}" class="btn-ghost btn-sm mb-4">
            ← Kembali ke daftar variant
        </a>

        <div class="card-flat animate-fade-up p-6">
            <form method="POST" action="{{ route('admin.product-variants.update', $variant) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="label">Pilih Produk <span class="text-rose-500">*</span></label>
                    <select name="product_id" required class="input">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                {{ (string) old('product_id', $variant->product_id) === (string) $product->id ? 'selected' : '' }}>
                                {{ $product->name }} — Rp {{ number_format($product->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">Warna</label>
                        <input type="text" name="color" value="{{ old('color', $variant->color) }}" class="input" placeholder="Contoh: Hitam">
                        @error('color')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Ukuran</label>
                        <input type="text" name="size" value="{{ old('size', $variant->size) }}" class="input" placeholder="Contoh: M">
                        @error('size')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="label">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $variant->sku) }}" class="input" placeholder="Contoh: TS-BLK-M">
                    @error('sku')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">Stok <span class="text-rose-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $variant->stock) }}" min="0" required class="input">
                        @error('stock')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Harga Variant <span class="text-slate-400">(opsional)</span></label>
                        <input type="number" name="price" value="{{ old('price', $variant->price) }}" min="0" step="1000" class="input" placeholder="Kosongkan = ikut harga produk">
                        @error('price')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="label">Gambar Variant</label>
                    <div class="flex items-center gap-4">
                        <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 dark:border-white/15 dark:bg-white/5">
                            @if ($variant->image_url)
                                <img x-show="!preview" src="{{ $variant->image_url }}" alt="Gambar {{ $variant->display_name }}" class="h-full w-full object-cover">
                            @else
                                <template x-if="!preview">
                                    <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </template>
                            @endif
                            <img x-show="preview" :src="preview" alt="Preview gambar baru" class="h-full w-full object-cover">
                        </div>
                        <div class="flex-1">
                            <label class="text-xs font-semibold text-slate-500">Ganti gambar <span class="font-normal text-slate-400">(opsional — kosongkan untuk mempertahankan gambar saat ini)</span></label>
                            <input type="file" name="image" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp"
                                @change="preview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : null"
                                class="mt-1 block w-full text-sm text-slate-500 file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-ink hover:file:bg-primary-dark">
                            <p class="mt-2 text-xs text-slate-400">JPG, PNG, WebP. Maksimal 2MB. Gambar lama otomatis dihapus setelah gambar baru tersimpan.</p>
                            @error('image')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5 dark:border-white/10">
                    <a href="{{ route('admin.product-variants.index') }}" class="btn-outline">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
