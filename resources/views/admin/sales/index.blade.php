@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
    {{-- Summary cards --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="card-flat p-5">
            <p class="text-xs text-slate-500">Total Pendapatan</p>
            <p class="mt-1 font-display text-xl font-extrabold text-primary dark:text-primary-soft">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="card-flat p-5">
            <p class="text-xs text-slate-500">Total Ongkir</p>
            <p class="mt-1 font-display text-xl font-extrabold">Rp {{ number_format($summary['shipping'], 0, ',', '.') }}</p>
        </div>
        <div class="card-flat p-5">
            <p class="text-xs text-slate-500">Jumlah Pesanan</p>
            <p class="mt-1 font-display text-xl font-extrabold">{{ $summary['orders'] }}</p>
        </div>
        <div class="card-flat p-5">
            <p class="text-xs text-slate-500">Item Terjual</p>
            <p class="mt-1 font-display text-xl font-extrabold">{{ $summary['items_sold'] }}</p>
        </div>
    </div>

    {{-- Filters & export --}}
    <div class="card-flat mt-6 p-5">
        <form action="{{ route('admin.sales.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div>
                <label class="label">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="input sm:w-48">
            </div>
            <div>
                <label class="label">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="input sm:w-48">
            </div>
            <button type="submit" class="btn-dark">Terapkan Filter</button>
            <div class="flex gap-2 sm:ml-auto">
                <a href="{{ route('admin.sales.export.csv', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}"
                    class="btn-outline">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export Excel
                </a>
                <a href="{{ route('admin.sales.print', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}"
                    target="_blank" class="btn-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2z"/></svg>
                    Export PDF
                </a>
            </div>
        </form>
    </div>

    {{-- Transactions table --}}
    <div class="table-wrap mt-6 animate-fade-up">
        <table class="table-base">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $t)
                    <tr>
                        <td class="font-mono text-xs font-bold">{{ $t->invoice_number }}</td>
                        <td class="text-slate-500">{{ $t->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary/20 text-xs font-bold">{{ strtoupper(substr($t->user?->name ?? '?', 0, 1)) }}</span>
                                <div class="min-w-0">
                                    <p class="truncate font-semibold">{{ $t->user?->name ?? 'Unknown' }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ $t->user?->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge-primary">{{ $t->payment_method }}</span></td>
                        <td><span class="badge {{ $t->status_class }}">{{ ucfirst($t->status) }}</span></td>
                        <td class="text-right font-bold">Rp {{ number_format($t->total_price + $t->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <p class="font-semibold">Tidak ada transaksi pada periode ini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($transactions->hasPages())
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    @endif
@endsection

