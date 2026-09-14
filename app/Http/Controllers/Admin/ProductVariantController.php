<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    /**
     * Daftar seluruh variant (menu standalone "Produk Variant").
     */
    public function index(Request $request): View
    {
        $query = ProductVariant::with('product')->latest();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('color', 'like', '%'.$search.'%')
                    ->orWhere('size', 'like', '%'.$search.'%')
                    ->orWhere('sku', 'like', '%'.$search.'%')
                    ->orWhereHas('product', fn ($p) => $p->where('name', 'like', '%'.$search.'%'));
            });
        }

        if ($request->filled('product_id') && $request->string('product_id') !== 'all') {
            $query->where('product_id', (int) $request->string('product_id'));
        }

        $variants = $query->paginate(12)->withQueryString();
        $products = Product::orderBy('name')->get(['id', 'name', 'price']);

        return view('admin.product-variants.index', compact('variants', 'products'));
    }

    /**
     * Form tambah variant (dropdown Pilih Produk).
     */
    public function create(Request $request): View
    {
        $products = Product::orderBy('name')->get(['id', 'name', 'price']);
        $selectedProductId = $request->integer('product_id') ?: old('product_id');

        return view('admin.product-variants.create', compact('products', 'selectedProductId'));
    }

    /**
     * Simpan variant baru (termasuk upload gambar bila ada).
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->variantRules());

        // Sinkronkan name lama agar cart/checkout/detail tetap kompatibel.
        if (empty($data['name'])) {
            $parts = array_filter([$data['color'] ?? null, $data['size'] ?? null]);
            $data['name'] = $parts ? implode(' - ', $parts) : 'Variant';
        }

        // Upload gambar: store() menghasilkan nama file unik (hash),
        // tidak memakai nama asli sehingga aman dari bentrok.
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product-variants', 'public');
        } else {
            unset($data['image']);
        }

        ProductVariant::create($data);

        return redirect()->route('admin.product-variants.index')
            ->with('success', 'Variant produk berhasil ditambahkan.');
    }

    /**
     * Form edit variant.
     */
    public function edit(ProductVariant $productVariant): View
    {
        $productVariant->load('product');
        $products = Product::orderBy('name')->get(['id', 'name', 'price']);

        return view('admin.product-variants.edit', [
            'variant' => $productVariant,
            'products' => $products,
        ]);
    }

    /**
     * Update variant (ganti gambar bila diupload file baru,
     * data lainnya tidak tersentuh).
     */
    public function update(Request $request, ProductVariant $productVariant): RedirectResponse
    {
        $data = $request->validate($this->variantRules($productVariant->id));

        if (empty($data['name'])) {
            $parts = array_filter([$data['color'] ?? null, $data['size'] ?? null]);
            $data['name'] = $parts ? implode(' - ', $parts) : ($productVariant->name ?? 'Variant');
        }

        if ($request->hasFile('image')) {
            // Simpan file baru dulu, lalu hapus file lama agar tidak
            // ada momen tanpa gambar bila penyimpanan gagal.
            $newPath = $request->file('image')->store('product-variants', 'public');
            $oldPath = $productVariant->image;
            $data['image'] = $newPath;
            $productVariant->update($data);
            if ($oldPath && $oldPath !== $newPath) {
                Storage::disk('public')->delete($oldPath);
            }
        } else {
            // Tanpa file baru: pertahankan gambar lama, update data lain saja.
            unset($data['image']);
            $productVariant->update($data);
        }

        return redirect()->route('admin.product-variants.index')
            ->with('success', 'Variant produk berhasil diperbarui.');
    }

    /**
     * Hapus variant (file gambar ikut terhapus via model event).
     */
    public function destroy(ProductVariant $productVariant): RedirectResponse
    {
        $productVariant->delete();

        return redirect()->route('admin.product-variants.index')
            ->with('success', 'Variant produk berhasil dihapus.');
    }

    /**
     * Validation rules (dipisah dari ProductController).
     *
     * @return array<string, mixed>
     */
    private function variantRules(?int $ignoreId = null): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:50'],
            'size' => ['nullable', 'string', 'max:20'],
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('product_variants', 'sku')->ignore($ignoreId)],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            // Gambar: validasi backend (MIME + ekstensi + ukuran).
            // Hanya JPG/JPEG, PNG, WebP, maks 2MB. File executable ditolak.
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ];
    }
}
