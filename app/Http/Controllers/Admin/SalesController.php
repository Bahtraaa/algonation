<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SalesController extends Controller
{
    /**
     * Sales report page with date range filter & revenue metrics.
     */
    public function index(Request $request): View
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $transactions = Transaction::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $revenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_price');

        $shipping = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('shipping_cost');

        $orders = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();

$itemsSold = DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->sum('transaction_details.quantity');

        $summary = [
            'revenue'    => $revenue,
            'shipping'   => $shipping,
            'orders'     => $orders,
            'items_sold' => $itemsSold,
        ];

        return view('admin.sales.index', compact('transactions', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Export sales report as CSV (Excel-compatible).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $transactions = Transaction::with('user', 'details.product')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $filename = 'sales-report-'.Carbon::now()->format('Ymd-His').'.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($transactions) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel

            fputcsv($handle, [
                'Invoice', 'Tanggal', 'Customer', 'Email',
                'Total Belanja', 'Ongkir', 'Total Bayar',
                'Pembayaran', 'Status', 'Item',
            ]);

            foreach ($transactions as $t) {
                $items = $t->details->map(function ($d) {
                    $variant = $d->variant ? ' ('.$d->variant->name.')' : '';

                    return $d->product?->name.$variant.' x'.$d->quantity;
                })->implode('; ');

                fputcsv($handle, [
                    $t->invoice_number,
                    $t->created_at->format('Y-m-d H:i'),
                    $t->user?->name ?? '-',
                    $t->user?->email ?? '-',
                    number_format($t->total_price, 0, ',', '.'),
                    number_format($t->shipping_cost, 0, ',', '.'),
                    number_format($t->total_price + $t->shipping_cost, 0, ',', '.'),
                    $t->payment_method,
                    $t->status,
                    $items,
                ]);
            }

            fclose($handle);
        };

        return Response::streamDownload($callback, $filename, $headers);
    }

    /**
     * Printable PDF-style report view (use browser print -> Save as PDF).
     */
    public function print(Request $request): View
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $transactions = Transaction::with('user', 'details.product', 'details.variant')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $revenue = $transactions->where('status', '!=', 'cancelled')->sum('total_price');
        $shipping = $transactions->where('status', '!=', 'cancelled')->sum('shipping_cost');

        return view('admin.sales.print', compact('transactions', 'startDate', 'endDate', 'revenue', 'shipping'));
    }
}

