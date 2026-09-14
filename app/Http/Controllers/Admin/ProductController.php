<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Show the product management page (data table).
     *
     * Halaman ini HANYA mengelola data utama produk.
     * Pengelolaan variant dipindah ke menu standalone "Produk Variant"
     * (ProductVariantController) dan tidak boleh ada form variant di sini.
     */
    public function index(Request $request): View
    {
        $query = Product::with('variants');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->string('search').'%');
        }

        if ($request->filled('category') && $request->string('category') !== 'all') {
            $query->where('category', $request->string('category'));
        }

        $products   = $query->latest()->paginate(8)->withQueryString();
        $categories = Product::CATEGORIES;

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the standalone create form (/admin/products/create).
     */
    public function create(): View
    {
        $categories = Product::CATEGORIES;

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->productRules());

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show the edit form.
     */
    public function edit(Product $product): View
    {
        $product->load('variants');
        $categories = Product::CATEGORIES;

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate($this->productRules());

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $this->storeImage($request);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Delete the specified product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Add stock to a product.
     */
    public function addStock(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate($this->stockRules());

        $product->increment('stock', $data['quantity']);

        return back()->with('success', "Stok +{$data['quantity']} berhasil ditambahkan.");
    }

    /**
     * Store a new variant for a product.
     */
    public function storeVariant(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate($this->variantRules());

        $product->variants()->create($data);

        return back()->with('success', 'Varian produk berhasil ditambahkan.');
    }

    /**
     * Update a variant.
     */
    public function updateVariant(Request $request, ProductVariant $variant): RedirectResponse
    {
        $data = $request->validate($this->variantRules());

        $variant->update($data);

        return back()->with('success', 'Varian produk berhasil diperbarui.');
    }

    /**
     * Add stock to a specific variant.
     */
    public function addVariantStock(Request $request, ProductVariant $variant): RedirectResponse
    {
        $data = $request->validate($this->stockRules());

        $variant->increment('stock', $data['quantity']);

        return back()->with('success', "Stok varian +{$data['quantity']} berhasil ditambahkan.");
    }

    /**
     * Delete a variant.
     */
    public function destroyVariant(ProductVariant $variant): RedirectResponse
    {
        $variant->delete();

        return back()->with('success', 'Varian produk berhasil dihapus.');
    }

    /**
     * Shared validation rules for creating/updating a product.
     *
     * @return array<string, list<string>>
     */
    private function productRules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'category'    => ['required', Rule::in(Product::CATEGORIES)],
            'status'      => ['nullable', Rule::in(Product::STATUSES)],
            'description' => ['nullable', 'string'],
            'stock'       => ['required', 'integer', 'min:0'],
            'price'       => ['required', 'numeric', 'min:0'],
            'weight'      => ['nullable', 'numeric', 'min:0'],
            'length'      => ['nullable', 'numeric', 'min:0'],
            'width'       => ['nullable', 'numeric', 'min:0'],
            'height'      => ['nullable', 'numeric', 'min:0'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ];
    }

    /**
     * Shared validation rules for creating/updating a variant.
     *
     * @return array<string, list<string>>
     */
    private function variantRules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Shared validation rules for stock increments.
     *
     * @return array<string, list<string>>
     */
    private function stockRules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Persist an uploaded product image and return its storage path.
     */
    private function storeImage(Request $request): string
    {
        return $request->file('image')->store('products', 'public');
    }
}

