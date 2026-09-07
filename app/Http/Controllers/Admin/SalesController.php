<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\SalesReportingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SalesController extends Controller
{
    public function __construct(
        private readonly SalesReportingService $salesReporting,
    ) {
    }

    /**
     * Sales report page with date range filter & revenue metrics.
     */
    public function index(Request $request): View
    {
        [$startDate, $endDate] = $this->dateRange($request);

        $transactions = Transaction::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $summary = [
            'revenue'    => $this->salesReporting->revenueBetween($startDate, $endDate),
            'shipping'   => $this->salesReporting->shippingBetween($startDate, $endDate),
            'orders'     => $this->salesReporting->orderCountBetween($startDate, $endDate),
            'items_sold' => $this->salesReporting->itemsSoldBetween($startDate, $endDate),
        ];

        return view('admin.sales.index', compact('transactions', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Export sales report as CSV (Excel-compatible).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        [$startDate, $endDate] = $this->dateRange($request);

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
                'Pembayaran', 'Status Pembayaran', 'Status Pesanan', 'Item',
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($handle, $this->csvRow($transaction));
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
        [$startDate, $endDate] = $this->dateRange($request);

        $transactions = Transaction::with('user', 'details.product', 'details.variant')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $revenue  = $this->salesReporting->revenueBetween($startDate, $endDate);
        $shipping = $this->salesReporting->shippingBetween($startDate, $endDate);

        return view('admin.sales.print', compact('transactions', 'startDate', 'endDate', 'revenue', 'shipping'));
    }

    /**
     * Resolve the report date range from request filters.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    private function dateRange(Request $request): array
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        return [$startDate, $endDate];
    }

    /**
     * Build a single CSV row from a transaction.
     *
     * @return list<string>
     */
    private function csvRow(Transaction $transaction): array
    {
        $items = $transaction->details->map(function ($detail) {
            $variant = $detail->variant ? ' ('.$detail->variant->name.')' : '';

            return $detail->product?->name.$variant.' x'.$detail->quantity;
        })->implode('; ');

        return [
            $transaction->invoice_number,
            $transaction->created_at->format('Y-m-d H:i'),
            $transaction->user?->name ?? '-',
            $transaction->user?->email ?? '-',
            number_format($transaction->total_price, 0, ',', '.'),
            number_format($transaction->shipping_cost, 0, ',', '.'),
            number_format($transaction->total_price + $transaction->shipping_cost, 0, ',', '.'),
            $transaction->payment_method_label,
            ucfirst($transaction->payment_status ?? 'pending'),
            ucfirst($transaction->status),
            $items,
        ];
    }
}