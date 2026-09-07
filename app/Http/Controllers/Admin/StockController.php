<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockController extends Controller
{
    /**
     * Stock report page with low-stock alerts & filters.
     */
    public function index(Request $request): View
    {
        $query = Product::with('variants');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->string('search').'%')
                ->orWhere('category', 'like', '%'.$request->string('search').'%');
        }

        if ($request->filled('filter')) {
            $filter = $request->string('filter');

            if ($filter === 'low') {
                $query->where('stock', '<=', Product::LOW_STOCK_THRESHOLD);
            } elseif ($filter === 'out') {
                $query->where('stock', '<=', 0);
            }
        }

        if ($request->filled('category') && $request->string('category') !== 'all') {
            $query->where('category', $request->string('category'));
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        $allProducts   = Product::with('variants')->get();
        $totalProducts = Product::count();
        $totalStock    = Product::sum('stock') + DB::table('product_variants')->sum('stock');
        $lowStockCount = $allProducts->filter(fn ($product) => $product->is_low_stock)->count();
        $outOfStockCount = $allProducts->filter(fn ($product) => $product->is_out_of_stock)->count();

        return view('admin.stock.index', compact(
            'products',
            'totalProducts',
            'totalStock',
            'lowStockCount',
            'outOfStockCount',
        ));
    }
}

