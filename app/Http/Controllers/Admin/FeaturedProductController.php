<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedProduct;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeaturedProductController extends Controller
{
    /**
     * List all featured products.
     */
    public function index(): View
    {
        $featured = FeaturedProduct::with('product.variants', 'product.activeFlashSale')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // Everything already in the catalog, used by the "add" dropdown.
        $allProducts = Product::with('activeFlashSale', 'featured')->orderBy('name')->get();

        // Products eligible to be featured: everything already in the catalog
        // that is NOT yet featured (no duplicate product rows are ever created).
        $available = $allProducts->filter(fn ($product) => ! $product->featured);

        return view('admin.featured-products.index', compact('featured', 'available', 'allProducts'));
    }

    /**
     * Add a product to the featured list.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'is_featured'=> ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $exists = FeaturedProduct::query()->where('product_id', $data['product_id'])->exists();

        if ($exists) {
            return back()->with('error', 'Produk tersebut sudah menjadi produk unggulan.');
        }

        FeaturedProduct::create([
            'product_id'  => $data['product_id'],
            'is_featured' => isset($data['is_featured']) ? (bool) $data['is_featured'] : true,
            'sort_order'  => $data['sort_order'] ?? 0,
        ]);

        return back()->with('success', 'Produk unggulan berhasil ditambahkan.');
    }

    /**
     * Update an existing featured product (product selection, status, order).
     */
    public function update(Request $request, FeaturedProduct $featuredProduct): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'is_featured'=> ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $duplicate = FeaturedProduct::query()
            ->where('product_id', $data['product_id'])
            ->where('id', '!=', $featuredProduct->id)
            ->exists();

        if ($duplicate) {
            return back()->with('error', 'Produk tersebut sudah menjadi produk unggulan.');
        }

        $featuredProduct->update([
            'product_id'  => $data['product_id'],
            'is_featured' => isset($data['is_featured']) ? (bool) $data['is_featured'] : true,
            'sort_order'  => $data['sort_order'] ?? 0,
        ]);

        return back()->with('success', 'Produk unggulan berhasil diperbarui.');
    }

    /**
     * Remove a product from the featured list.
     */
    public function destroy(FeaturedProduct $featuredProduct): RedirectResponse
    {
        $featuredProduct->delete();

        return back()->with('success', 'Produk unggulan berhasil dihapus.');
    }
}