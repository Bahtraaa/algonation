<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Services\SalesReportingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(
        private readonly SalesReportingService $salesReporting,
    ) {
    }

    /**
     * Admin dashboard with analytics & revenue charts.
     */
    public function dashboard(Request $request): View
    {
        $days = (int) $request->integer('days', 30);
        $days = in_array($days, [7, 30, 90]) ? $days : 30;

        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfDay();

        // Summary cards - only count PAID transactions as revenue.
        $totalRevenue  = $this->salesReporting->revenueBetween($start, $end);
        $totalOrders   = Transaction::count();
        $totalProducts = Product::count();
        $totalUsers    = User::where('role', 'user')->count();

        // Revenue by day (for chart) - only PAID transactions.
        $revenueByDay = $this->salesReporting->revenueByDay($start, $end);

        $chart = $this->buildChart($revenueByDay, $start, $days);

        // Payment status breakdown (for doughnut chart).
        $ordersByStatus = [
            'Menunggu Pembayaran' => $this->countByPaymentStatus('pending'),
            'Sudah Dibayar'       => $this->countByPaymentStatus('paid'),
            'Gagal'               => $this->countByPaymentStatus('failed'),
            'Dibatalkan'          => $this->countByPaymentStatus('cancelled'),
            'Kedaluwarsa'         => $this->countByPaymentStatus('expired'),
        ];

        // Low stock products.
        $lowStockProducts = Product::with('variants')->get()
            ->filter(fn ($product) => $product->is_low_stock)
            ->sortBy('total_stock')
            ->take(6);

        // Recent users.
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'chart',
            'ordersByStatus',
            'lowStockProducts',
            'recentUsers',
            'days'
        ));
    }

    /**
     * Count transactions by a given payment status.
     */
    private function countByPaymentStatus(string $paymentStatus): int
    {
        return Transaction::where('payment_status', $paymentStatus)->count();
    }

    /**
     * Build the day-labelled revenue series used by the chart.
     *
     * @return array<string, list<mixed>>
     */
    private function buildChart(\Illuminate\Support\Collection $revenueByDay, Carbon $start, int $days): array
    {
        $labels = [];
        $revenueByLabel = [];

        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($day)->format('d M');
            $revenueByLabel[] = $revenueByDay[$day] ?? 0;
        }

        return [
            'labels'  => $labels,
            'revenue' => $revenueByLabel,
        ];
    }
}