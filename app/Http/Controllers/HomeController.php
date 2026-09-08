<?php

namespace App\Http\Controllers;

use App\Models\FeaturedProduct;
use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Landing page.
     */
    public function index(): View
    {
        FlashSale::syncAllStatuses();

        $categories = Product::CATEGORIES;
        $featured = $this->featuredProducts()->take(8);
        $flashSales = FlashSale::active()->with('product.activeFlashSale')->latest()->get();

        return view('landing', compact('categories', 'featured', 'flashSales'));
    }

    /**
     * Shop / browse page with instant search & category filter.
     */
    public function shop(Request $request): View
    {
        FlashSale::syncAllStatuses();

        $query = Product::with('variants', 'activeFlashSale');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $search = $request->string('search');
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('category', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('category') && $request->string('category') !== 'all') {
            $query->where('category', $request->string('category'));
        }

        $products = $query->paginate(9)->withQueryString();
        $categories = Product::CATEGORIES;
        $selected = $request->string('category', 'all');

        return view('shop', compact('products', 'categories', 'selected'));
    }

    /**
     * Featured products page (the real featured collection).
     */
    public function featured(): View
    {
        FlashSale::syncAllStatuses();

        $products = $this->featuredProducts();

        return view('featured', compact('products'));
    }

    /**
     * Flash sale products page.
     *
     * The list itself is rendered client-side from the /api/flash-sales
     * endpoint, so no server-side query is needed here.
     */
    public function flashSale(): View
    {
        return view('flash-sale');
    }

    /**
     * About us page.
     */
    public function about(): View
    {
        return view('about');
    }

    /**
     * Single product detail page.
     */
    public function show(Product $product): View
    {
        $product->load('variants', 'activeFlashSale');

        $related = Product::with('variants', 'activeFlashSale')
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }

    /**
     * The featured products (referencing existing products), in insertion order.
     * Returns a Collection to keep sorting trivial.
     */
    private function featuredProducts(): Collection
    {
        return FeaturedProduct::query()
            ->with(['product.variants', 'product.activeFlashSale'])
            ->orderBy('id')
            ->get()
            ->pluck('product')
            ->filter();
    }
}
