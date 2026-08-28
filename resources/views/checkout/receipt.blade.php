@extends('layouts.app')

@section('title', 'Struk Pesanan — ALGO NATION')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        {{-- Success header --}}
        <div class="mb-8 text-center no-print animate-scale-in">
            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-soft">
                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 class="font-display text-2xl font-extrabold sm:text-3xl">Pesanan Berhasil Dibuat! 🎉</h1>
            <p class="mt-2 text-sm text-slate-500">Terima kasih telah berbelanja di ALGO NATION. Pembayaran dilakukan saat barang tiba.</p>
            <div class="mt-5 flex flex-wrap justify-center gap-3">
                <button onclick="window.print()" class="btn-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2z"/></svg>
                    Cetak Struk / PDF
                </button>
                <a href="{{ route('shop') }}" class="btn-outline">Belanja Lagi</a>
                <a href="{{ route('orders') }}" class="btn-outline">Lihat Pesanan Saya</a>
            </div>
        </div>

        {{-- Receipt --}}
        <div id="receipt" class="print-area glass-strong overflow-hidden rounded-3xl">
            {{-- Brand --}}
            <div class="border-b-2 border-dashed border-slate-200 p-8 text-center dark:border-white/10">
                <img src="{{ asset('images/logo-an.png') }}" alt="ALGO NATION" class="mx-auto mb-3 h-14 w-14 rounded-2xl object-cover shadow-lg shadow-primary/30" onerror="this.onerror=null; this.src='https://placehold.co/100x100/e9f50b/1b1b18?text=AN';">
                <h2 class="font-display text-xl font-extrabold tracking-wide">ALGO NATION</h2>
                <p class="mt-1 text-xs text-slate-500">Urban Apparel Store</p>
                <p class="mt-0.5 text-xs text-slate-500">Jl. Senja No. 88, Jakarta Selatan</p>
                <p class="mt-0.5 text-xs text-slate-500">0812-9000-2310</p>
            </div>

            {{-- Meta --}}
            <div class="border-b-2 border-dashed border-slate-200 px-8 py-6 dark:border-white/10">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-slate-400">No. Invoice</p>
                        <p class="font-bold">{{ $transaction->invoice_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider text-slate-400">Tanggal</p>
                        <p class="font-bold">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider text-slate-400">Pembayaran</p>
                        <p class="font-bold">Cash on Delivery</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider text-slate-400">Status</p>
                        <span class="badge {{ $transaction->status_class }}">{{ ucfirst($transaction->status) }}</span>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs uppercase tracking-wider text-slate-400">Dikirim Ke</p>
                        <p class="whitespace-pre-line font-semibold">{{ $transaction->shipping_address }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs uppercase tracking-wider text-slate-400">Estimasi Tiba</p>
                        <p class="font-semibold">{{ $transaction->estimated_delivery }}</p>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="border-b-2 border-dashed border-slate-200 px-8 py-6 dark:border-white/10">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-xs uppercase tracking-wider text-slate-400 dark:border-white/10">
                            <th class="py-2">Produk</th>
                            <th class="py-2 text-center">Qty</th>
                            <th class="py-2 text-right">Harga</th>
                            <th class="py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->details as $detail)
                            <tr class="border-b border-slate-50 dark:border-white/5">
                                <td class="py-3">
                                    <span class="font-semibold">{{ $detail->product?->name ?? 'Produk' }}</span>
                                    @if ($detail->variant)
                                        <span class="block text-xs text-slate-500">{{ $detail->variant->name }}</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">{{ $detail->quantity }}</td>
                                <td class="py-3 text-right">Rp {{ number_format($detail->quantity > 0 ? $detail->subtotal / $detail->quantity : 0, 0, ',', '.') }}</td>
                                <td class="py-3 text-right font-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Totals --}}
            <div class="px-8 py-6">
                <div class="ml-auto max-w-xs space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Subtotal</span>
                        <span class="font-semibold">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Ongkos Kirim</span>
                        <span class="font-semibold {{ $transaction->shipping_cost === 0 ? 'text-primary' : '' }}">
                            {{ $transaction->shipping_cost === 0 ? 'GRATIS' : 'Rp '.number_format($transaction->shipping_cost, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between border-t-2 border-dashed border-slate-200 pt-3 dark:border-white/10">
                        <span class="font-display text-base font-extrabold">Total Bayar</span>
                        <span class="font-display text-lg font-extrabold text-primary dark:text-primary-soft">Rp {{ number_format($transaction->total_price + $transaction->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Footer note --}}
            <div class="border-t border-slate-100 px-8 py-6 text-center dark:border-white/10">
                <p class="text-xs text-slate-500">Siapkan uang pas saat kurir tiba. Simpan struk ini sebagai bukti pembelian.</p>
                <p class="mt-1 text-xs font-semibold text-slate-400">Terima kasih telah berbelanja di ALGO NATION! 🙏</p>
            </div>
        </div>
    </div>
@endsection

