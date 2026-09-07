@extends('layouts.app')

@section('title', 'Pesanan Saya - ALGO NATION')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="animate-fade-up">
            <p class="text-xs font-bold uppercase tracking-widest text-primary dark:text-primary-soft">Histori</p>
            <h1 class="mt-1 font-display text-3xl font-extrabold">Pesanan Saya</h1>
        </div>

        <div class="mt-8 space-y-4">
            @forelse ($transactions as $transaction)
                <div class="card-flat animate-fade-up overflow-hidden" style="animation-delay: {{ $loop->index * 60 }}ms">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 dark:border-white/10">
                        <div>
                            <p class="font-bold">{{ $transaction->invoice_number }}</p>
                            <p class="text-xs text-slate-500">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <x-status-badge :variant="$transaction->payment_status_class">{{ $transaction->payment_status_label }}</x-status-badge>
                            <x-status-badge :variant="$transaction->shipping_status_class">{{ $transaction->shipping_status_label }}</x-status-badge>
                            <span class="font-display text-lg font-extrabold text-primary dark:text-primary-soft">Rp {{ number_format($transaction->total_price + $transaction->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4">
                        <div class="flex -space-x-3">
                            @foreach ($transaction->details->take(4) as $detail)
                                <img src="{{ $detail->product?->image_url ?? 'https://placehold.co/100' }}" alt="{{ $detail->product?->name }}"
                                    class="h-11 w-11 rounded-full border-2 border-white object-cover dark:border-slate-900">
                            @endforeach
                            <span class="flex h-11 w-11 items-center justify-center rounded-full border-2 border-white bg-slate-100 text-xs font-bold dark:border-slate-900 dark:bg-slate-800">
                                +{{ $transaction->details->sum('quantity') }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('orders.show', $transaction) }}" class="btn-primary btn-sm">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat Detail Pesanan
                            </a>
                            <a href="{{ route('checkout.receipt', $transaction) }}" class="btn-outline btn-sm">Struk</a>
                            <button onclick="window.open('{{ route('checkout.receipt', $transaction) }}', '_blank')" class="btn-outline btn-sm">Cetak</button>

                            @if ($transaction->isPayable())
                                <button
                                    type="button"
                                    class="btn-primary btn-sm pay-now-btn"
                                    data-pay-url="{{ route('orders.pay', $transaction) }}"
                                    data-csrf="{{ csrf_token() }}"
                                >
                                    Bayar Sekarang
                                </button>
                            @endif

                            @if ($transaction->can_be_cancelled)
                                <form
                                    id="cancel-form-{{ $transaction->id }}"
                                    action="{{ route('orders.cancel', $transaction) }}"
                                    method="POST"
                                    onsubmit="event.preventDefault(); window.confirmAction('Yakin ingin membatalkan pesanan <strong>{{ $transaction->invoice_number }}</strong>? Stok produk akan dikembalikan.', () => document.getElementById('cancel-form-{{ $transaction->id }}').submit());"
                                    class="inline"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm">Batalkan</button>
                                </form>
                            @elseif ($transaction->shipping_status === 'diserahkan_ke_kurir')
                                <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">Paket sudah diserahkan kepada kurir dan tidak dapat dibatalkan.</span>
                            @elseif ($transaction->shipping_status === 'dalam_perjalanan')
                                <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">Paket sedang dalam perjalanan dan tidak dapat dibatalkan.</span>
                            @elseif (in_array($transaction->status, ['pending_payment', 'pending', 'processing']))
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500 dark:bg-white/5 dark:text-slate-400"
                                    title="Pesanan ini sudah tidak dapat dibatalkan karena akan segera tiba."
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Tidak bisa dibatalkan
                                </span>
                            @endif
                        </div>
                    </div>
                    @if (in_array($transaction->status, ['pending_payment', 'pending', 'processing']))
                        <div class="flex items-center justify-between gap-2 border-t border-slate-100 bg-slate-50/50 px-5 py-2.5 text-xs text-slate-500 dark:border-white/10 dark:bg-white/2 dark:text-slate-400">
                            <span>
                                Estimasi tiba:
                                <strong class="text-slate-700 dark:text-slate-200">{{ $transaction->estimated_delivery }}</strong>
                            </span>
                            <span>
                                @if (($transaction->payment_status ?? 'pending') === 'pending' && $transaction->payment_due_at)
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-amber-600 dark:text-amber-400">Sisa waktu bayar:</span>
                                        <strong class="font-mono text-amber-600 dark:text-amber-400" data-countdown-due="{{ $transaction->payment_due_at->toIso8601String() }}">--:--</strong>
                                    </span>
                                @elseif ($transaction->can_be_cancelled)
                                    Batas pembatalan: <strong class="text-rose-600 dark:text-rose-400">{{ $transaction->cancellation_deadline }}</strong>
                                @else
                                    <strong class="text-rose-600 dark:text-rose-400">Menunggu pengiriman</strong>
                                @endif
                            </span>
                        </div>
                    @endif

                    @if (in_array($transaction->payment_status, ['cancelled', 'expired']))
                        <div class="border-t border-slate-100 bg-rose-50/50 px-5 py-2.5 text-xs font-semibold text-rose-600 dark:border-white/10 dark:bg-rose-500/10 dark:text-rose-400">
                            {{ $transaction->payment_status === 'expired'
                                ? 'Pembayaran Kedaluwarsa - Pesanan ini dibatalkan karena pembayaran tidak diselesaikan dalam 15 menit.'
                                : 'Pesanan Dibatalkan - Pesanan ini sudah dibatalkan dan tidak dapat dibayar kembali.' }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="card-flat py-16 text-center">
                    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 dark:bg-white/5">
                        <svg class="h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold">Belum Ada Pesanan</h3>
                    <p class="mt-1 text-sm text-slate-500">Yuk mulai belanja dan buat pesanan pertamamu!</p>
                    <a href="{{ route('shop') }}" class="btn-primary mt-6">Mulai Belanja</a>
                </div>
            @endforelse
        </div>

        @if ($transactions->hasPages())
            <div class="mt-8">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script src="{{ config('midtrans.isProduction') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.clientKey') }}"></script>
<script>
    // Shared Midtrans Snap integration + display-only payment deadline timers.
    window.initPayNowButtons();
    window.startDueCountdowns();
</script>
@endpush
