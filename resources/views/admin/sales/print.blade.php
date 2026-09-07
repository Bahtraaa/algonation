<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penjualan {{ $startDate->format('d/m/Y') }} sampai {{ $endDate->format('d/m/Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #1b1b18; font-size: 13px; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #1b1b18; padding-bottom: 20px; margin-bottom: 24px; }
        .brand h1 { font-size: 22px; letter-spacing: 1px; }
        .brand p { color: #666; font-size: 11px; margin-top: 2px; }
        .report-meta { text-align: right; font-size: 12px; }
        .report-meta strong { display: block; font-size: 16px; margin-top: 4px; }
        .summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 24px; }
        .summary div { border: 1px solid #ddd; border-radius: 8px; padding: 12px; }
        .summary .label { font-size: 11px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary .value { font-size: 16px; font-weight: 700; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        thead th { background: #1b1b18; color: #fff; padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        tbody td { padding: 10px 12px; border-bottom: 1px solid #eee; }
        tbody tr:nth-child(even) { background: #fafafa; }
        .text-right { text-align: right; }
        .status { display: inline-block; padding: 2px 10px; border-radius: 15px; font-size: 11px; font-weight: 600; text-transform: capitalize; }
        .status-pending { background: #e8dcc8; color: #5c3d25; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-failed { background: #ffe4e6; color: #9f1239; }
        .status-cancelled { background: #ffe4e6; color: #9f1239; }
        .status-expired { background: #fef3c7; color: #92400e; }
        .footer { margin-top: 32px; text-align: center; color: #888; font-size: 11px; border-top: 1px solid #ddd; padding-top: 16px; }
        .no-print { display: none; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="display:flex; justify-content:flex-end; gap:8px; margin-bottom:16px;">
        <button onclick="window.print()" style="background:#8d5e42; border:none; padding:10px 20px; border-radius:8px; font-weight:700; cursor:pointer; color:#fff;">Cetak / Simpan PDF</button>
    </div>

    <div class="header">
        <div class="brand">
            <h1>ALGO NATION</h1>
            <p>Urban Apparel Store</p>
            <p>Jl. Senja No. 88, Jakarta Selatan</p>
        </div>
        <div class="report-meta">
            <span>Laporan Penjualan</span>
            <strong>{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</strong>
            <span>Dibuat: {{ now()->format('d M Y H:i') }}</span>
        </div>
    </div>

    <div class="summary">
        <div>
            <p class="label">Total Pendapatan</p>
            <p class="value">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
        </div>
        <div>
            <p class="label">Total Ongkir</p>
            <p class="value">Rp {{ number_format($shipping, 0, ',', '.') }}</p>
        </div>
        <div>
            <p class="label">Jumlah Transaksi</p>
            <p class="value">{{ $transactions->count() }}</p>
        </div>
        <div>
            <p class="label">Total Pendapatan</p>
            <p class="value">Rp {{ number_format($revenue + $shipping, 0, ',', '.') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Item</th>
                <th>Status Pembayaran</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Ongkir</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $t)
                <tr>
                    <td>{{ $t->invoice_number }}</td>
                    <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $t->user?->name ?? '-' }}</td>
                    <td>
                        @foreach ($t->details as $d)
                            <div>{{ $d->product?->name ?? 'Produk' }}{{ $d->variant ? ' ('.$d->variant->name.')' : '' }} × {{ $d->quantity }}</div>
                        @endforeach
                    </td>
                    <td><span class="status status-{{ $t->payment_status ?? 'pending' }}">{{ ucfirst($t->payment_status ?? 'pending') }}</span></td>
                    <td class="text-right">Rp {{ number_format($t->total_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($t->shipping_cost, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($t->total_price + $t->shipping_cost, 0, ',', '.') }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:40px;">Tidak ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat otomatis oleh sistem ALGO NATION.</p>
        <p>Dicetak pada {{ now()->format('d M Y, H:i:s') }}</p>
    </div>
</body>
</html>

