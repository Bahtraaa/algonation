<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Landing page.
     */
    public function index(): View
    {
        $categories = Product::CATEGORIES;
        $featured   = Product::with('variants')->take(8)->get();

        return view('landing', compact('categories', 'featured'));
    }

    /**
     * Shop / browse page with instant search & category filter.
     */
    public function shop(Request $request): View
    {
        $query = Product::with('variants');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->string('search').'%')
                ->orWhere('description', 'like', '%'.$request->string('search').'%')
                ->orWhere('category', 'like', '%'.$request->string('search').'%');
        }

        if ($request->filled('category') && $request->string('category') !== 'all') {
            $query->where('category', $request->string('category'));
        }

        $products   = $query->paginate(9)->withQueryString();
        $categories = Product::CATEGORIES;
        $selected   = $request->string('category', 'all');

        return view('shop', compact('products', 'categories', 'selected'));
    }

    /**
     * Featured products page.
     */
    public function featured(): View
    {
        $products = Product::with('variants')->take(8)->get();

        return view('featured', compact('products'));
    }

    /**
     * Flash sale products page.
     */
    public function flashSale(): View
    {
        $products = Product::with('variants')->take(8)->get();

        return view('flash-sale', compact('products'));
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
        $product->load('variants');

        $related = Product::with('variants')
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}

