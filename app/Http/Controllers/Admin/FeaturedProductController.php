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
        $featured = FeaturedProduct::with([
                'product.variants',
                'product.flashSale',
                'product.activeFlashSale',
            ])
            ->orderBy('id')
            ->get();

        // Products eligible to be featured: everything already in the catalog
        // that is NOT yet featured (no duplicate product rows are ever created).
        $available = Product::with('flashSale', 'activeFlashSale', 'featured')
            ->orderBy('name')
            ->get()
            ->filter(fn ($product) => ! $product->featured);

        return view('admin.featured-products.index', compact('featured', 'available'));
    }

    /**
     * Add a product to the featured list.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $exists = FeaturedProduct::query()->where('product_id', $data['product_id'])->exists();

        if ($exists) {
            return back()->with('error', 'Produk tersebut sudah menjadi produk unggulan.');
        }

        FeaturedProduct::create(['product_id' => $data['product_id']]);

        return back()->with('success', 'Produk unggulan berhasil ditambahkan.');
    }

    /**
     * Change which product a featured entry points to.
     * Harga tidak diminta — otomatis mengikuti Product + Flash Sale.
     */
    public function update(Request $request, FeaturedProduct $featuredProduct): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $exists = FeaturedProduct::query()
            ->where('product_id', $data['product_id'])
            ->where('id', '!=', $featuredProduct->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Produk tersebut sudah menjadi produk unggulan.');
        }

        $featuredProduct->update(['product_id' => $data['product_id']]);

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
