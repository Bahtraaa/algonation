<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Sales-report aggregation used by the admin dashboard and the sales report.
 *
 * Revenue is always derived from PAID transactions only, so the
 * dashboard and the report stay consistent in what counts as sales.
 */
class SalesReportingService
{
    /**
     * Total revenue (product subtotal) from paid transactions in a date range.
     */
    public function revenueBetween(\Carbon\Carbon $start, \Carbon\Carbon $end): float
    {
        return (float) $this->paidQueryBetween($start, $end)->sum('total_price');
    }

    /**
     * Total shipping collected from paid transactions in a date range.
     */
    public function shippingBetween(\Carbon\Carbon $start, \Carbon\Carbon $end): float
    {
        return (float) $this->paidQueryBetween($start, $end)->sum('shipping_cost');
    }

    /**
     * Count of transactions created in a date range (any payment status).
     */
    public function orderCountBetween(\Carbon\Carbon $start, \Carbon\Carbon $end): int
    {
        return Transaction::whereBetween('created_at', [$start, $end])->count();
    }

    /**
     * Quantity of items sold (from paid transactions only) in a date range.
     */
    public function itemsSoldBetween(\Carbon\Carbon $start, \Carbon\Carbon $end): int
    {
        return (int) DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->whereBetween('transactions.created_at', [$start, $end])
            ->where('transactions.payment_status', 'paid')
            ->sum('transaction_details.quantity');
    }

    /**
     * Paid-revenue aggregation grouped by day, keyed by "Y-m-d".
     *
     * Note: this includes shipping, matching the dashboard chart definition.
     */
    public function revenueByDay(\Carbon\Carbon $start, \Carbon\Carbon $end): Collection
    {
        return Transaction::where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('date(created_at) as day, sum(total_price + shipping_cost) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->map(fn ($value) => (float) $value);
    }

    /**
     * Base query restricted to paid transactions within a date range.
     */
    private function paidQueryBetween(\Carbon\Carbon $start, \Carbon\Carbon $end)
    {
        return Transaction::whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid');
    }
}