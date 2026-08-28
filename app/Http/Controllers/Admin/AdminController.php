<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Admin dashboard with analytics & revenue charts.
     */
    public function dashboard(Request $request): View
    {
        $days = (int) $request->integer('days', 30);
        $days = in_array($days, [7, 30, 90]) ? $days : 30;

        $start = Carbon::now()->subDays($days - 1)->startOfDay();

        // Summary cards
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfDay();

        $totalRevenue  = Transaction::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->sum('total_price');
        $totalOrders   = Transaction::count();
        $totalProducts = Product::count();
        $totalUsers    = User::where('role', 'user')->count();
        $lowStock      = Product::with('variants')->get()->filter(fn ($p) => $p->is_low_stock)->count();

        // Revenue by day (for chart)
        $revenueByDay = Transaction::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $start)
            ->selectRaw('date(created_at) as day, sum(total_price + shipping_cost) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->map(fn ($v) => (float) $v);

        $chart = [
            'labels' => [],
            'revenue' => [],
        ];

        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i)->format('Y-m-d');
            $chart['labels'][] = Carbon::parse($day)->format('d M');
            $chart['revenue'][] = $revenueByDay[$day] ?? 0;
        }

        // Orders by status (for doughnut chart)
        $ordersByStatus = [
            'pending'    => Transaction::where('status', 'pending')->count(),
            'processing' => Transaction::where('status', 'processing')->count(),
            'completed'  => Transaction::where('status', 'completed')->count(),
            'cancelled'  => Transaction::where('status', 'cancelled')->count(),
        ];

        // Low stock products
        $lowStockProducts = Product::with('variants')->get()
            ->filter(fn ($p) => $p->is_low_stock)
            ->sortBy('total_stock')
            ->take(6);

        // Recent users
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'lowStock',
            'chart',
            'ordersByStatus',
            'lowStockProducts',
            'recentUsers',
            'days'
        ));
    }
}

