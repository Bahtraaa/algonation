@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $transaction->id . ' - ALGO NATION')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6 animate-fade-up">
            <a href="{{ route('orders') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Pesanan Saya
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Left: Order Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Order Header --}}
                <div class="card-flat p-6 animate-fade-up">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-primary dark:text-primary-soft">Detail Pesanan</p>
                            <h1 class="mt-1 font-display text-2xl font-extrabold">{{ $transaction->invoice_number }}</h1>
                            <p class="text-xs text-slate-500">Dipesan pada {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <x-status-badge :variant="$transaction->payment_status_class">{{ $transaction->payment_status_label }}</x-status-badge>
                            <x-status-badge :variant="$transaction->shipping_status_class">{{ $transaction->shipping_status_label }}</x-status-badge>
                        </div>
                    </div>
                </div>

                {{-- Tracking Timeline --}}
                <div class="card-flat p-6 animate-fade-up" style="animation-delay: 60ms">
                    <h2 class="mb-5 text-sm font-bold">Tracking Pengiriman</h2>
                    @php
                        $steps = \App\Models\Transaction::trackingSteps();
                        $stepLabels = [
                            'pesanan_dibuat'       => $transaction->payment_status === 'pending' ? 'Menunggu proses pembayaran' : 'Pesanan Dibuat',
                            'pembayaran_berhasil'  => 'Pembayaran Berhasil',
                            'pesanan_diproses'     => 'Pesanan Diproses',
                            'dikemas'              => 'Dikemas',
                            'diserahkan_ke_kurir'  => 'Diserahkan ke Kurir',
                            'dalam_perjalanan'     => 'Dalam Perjalanan',
                            'sedang_diantar'       => 'Sedang Diantar',
                            'pesanan_diterima'     => 'Pesanan Diterima',
                        ];
                        $currentIdx = $transaction->getCurrentTrackingIndex();
                    @endphp
                    <div class="space-y-0">
                        @foreach ($steps as $idx => $step)
                            @php
                                $isCompleted = $currentIdx >= 0 && $idx < $currentIdx;
                                $isActive = $idx === $currentIdx && $currentIdx >= 0;
                                $isPending = $idx > $currentIdx || $currentIdx < 0;
                            @endphp
                            <div class="flex items-start gap-3">
                                <div class="flex flex-col items-center">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold
                                        {{ $isActive ? 'bg-primary text-ink ring-4 ring-primary/20 scale-110' : ($isCompleted ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 dark:bg-white/5 dark:text-slate-500') }}">
                                        @if ($isCompleted)
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        @else
                                            {{ $idx + 1 }}
                                        @endif
                                    </div>
                                    @if (!$loop->last)
                                        <div class="h-8 w-0.5 {{ $isCompleted || $isActive ? 'bg-emerald-400' : 'bg-slate-200 dark:bg-white/10' }}"></div>
                                    @endif
                                </div>
                                <div class="pt-1">
                                    <p class="text-sm font-semibold {{ $isActive ? 'text-primary dark:text-primary-soft' : ($isCompleted ? 'text-slate-700 dark:text-slate-200' : 'text-slate-400') }}">
                                        {{ $stepLabels[$step] }}
                                    </p>
                                    @if ($isActive)
                                        <p class="mt-0.5 text-xs text-primary/70">Status saat ini</p>
                                    @endif
                                    @if ($isCompleted)
                                        <p class="mt-0.5 text-[10px] text-emerald-500">Selesai</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($transaction->shipping_status === 'pengiriman_gagal')
                        <div class="mt-4 rounded-lg bg-rose-50 p-3 text-xs font-semibold text-rose-600 dark:bg-rose-500/10 dark:text-rose-400">Pengiriman mengalami kendala. Silakan hubungi admin untuk informasi lebih lanjut.</div>
                    @endif
                    @if ($transaction->shipping_status === 'dibatalkan')
                        <div class="mt-4 rounded-lg bg-rose-50 p-3 text-xs font-semibold text-rose-600 dark:bg-rose-500/10 dark:text-rose-400">Pesanan ini telah dibatalkan.</div>
                    @endif
                </div>

                {{-- Items --}}
                <div class="card-flat p-6 animate-fade-up" style="animation-delay: 120ms">
                    <h2 class="mb-4 text-sm font-bold">Item Pesanan</h2>
                    <div class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($transaction->details as $detail)
                            <div class="flex items-center gap-4 py-3">
                                <img src="{{ $detail->product?->image_url ?? 'https://placehold.co/100' }}" alt="" class="h-14 w-14 rounded-xl object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold">{{ $detail->product?->name ?? 'Produk' }}</p>
                                    @if ($detail->variant)
                                        <p class="text-xs text-slate-500">{{ $detail->variant->display_name }}</p>
                                    @endif
                                    <p class="text-xs text-slate-500">{{ $detail->quantity }} x Rp {{ number_format($detail->subtotal / max($detail->quantity, 1), 0, ',', '.') }}</p>
                                </div>
                                <span class="text-sm font-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 border-t border-slate-100 pt-4 dark:border-white/10">
                        <div class="flex justify-between text-sm"><span class="text-slate-500">Subtotal Produk</span><span class="font-semibold">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between text-sm"><span class="text-slate-500">Ongkos Kirim</span><span class="font-semibold">{{ $transaction->shipping_cost > 0 ? 'Rp ' . number_format($transaction->shipping_cost, 0, ',', '.') : 'Gratis' }}</span></div>
                        <div class="flex justify-between text-base font-bold mt-2 pt-2 border-t border-slate-100 dark:border-white/10"><span>Total Pembayaran</span><span class="text-primary dark:text-primary-soft">Rp {{ number_format($transaction->total_price + $transaction->shipping_cost, 0, ',', '.') }}</span></div>
                    </div>
                </div>

                {{-- Shipping Address (snapshot — tidak berubah walau alamat akun diedit) --}}
                <div class="card-flat p-6 animate-fade-up" style="animation-delay: 180ms">
                    <h2 class="mb-3 text-sm font-bold">Alamat Pengiriman</h2>
                    @if ($transaction->has_address_snapshot)
                        @if ($transaction->shipping_label)
                            <span class="mb-2 inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold dark:bg-white/10">{{ $transaction->shipping_label }}</span>
                        @endif
                        <p class="text-sm font-bold">{{ $transaction->shipping_name }}</p>
                        <p class="text-sm text-slate-500">{{ $transaction->shipping_phone }}</p>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $transaction->shipping_address }}</p>
                        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $transaction->shipping_district }}, {{ $transaction->shipping_city }}</p>
                        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $transaction->shipping_province }}, {{ $transaction->shipping_postal_code }}</p>
                        <p class="text-sm text-slate-500">{{ $transaction->shipping_country }}</p>
                        @if ($transaction->shipping_note)
                            <p class="mt-1 text-xs text-slate-400">Catatan: {{ $transaction->shipping_note }}</p>
                        @endif
                    @else
                        <p class="whitespace-pre-line text-sm text-slate-600 dark:text-slate-300">{{ $transaction->shipping_display }}</p>
                    @endif
                </div>
            </div>

            {{-- Right: Shipping Info --}}
            <div class="space-y-6">
                {{-- Shipping Details --}}
                <div class="card-flat p-6 animate-fade-up" style="animation-delay: 120ms">
                    <h2 class="mb-4 text-sm font-bold">Informasi Pengiriman</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start justify-between gap-3">
                            <span class="text-slate-500">Kurir</span>
                            <span class="font-semibold text-right">{{ $transaction->shipping_courier ?? '-' }}</span>
                        </div>
                        @if ($transaction->tracking_number)
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-slate-500">No. Resi</span>
                                <span class="font-mono font-semibold text-right break-all">{{ $transaction->tracking_number }}</span>
                            </div>
                        @endif
                        <div class="flex items-start justify-between gap-3">
                            <span class="text-slate-500">Status</span>
                            <x-status-badge :variant="$transaction->shipping_status_class">{{ $transaction->shipping_status_label }}</x-status-badge>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <span class="text-slate-500">Estimasi Tiba</span>
                            <span class="font-semibold text-right">{{ $transaction->estimated_delivery }}</span>
                        </div>
                        @if ($transaction->shipped_at)
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-slate-500">Dikirim</span>
                                <span class="font-semibold">{{ $transaction->shipped_at->format('d M Y') }}</span>
                            </div>
                        @endif
                        @if ($transaction->delivered_at)
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-slate-500">Diterima</span>
                                <span class="font-semibold text-emerald-600">{{ $transaction->delivered_at->format('d M Y, H:i') }}</span>
                            </div>
                        @endif
                        @if ($transaction->shipping_updated_at)
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-slate-500">Terakhir Update</span>
                                <span class="text-xs text-slate-400">{{ $transaction->shipping_updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment Info --}}
                <div class="card-flat p-6 animate-fade-up" style="animation-delay: 180ms">
                    <h2 class="mb-4 text-sm font-bold">Informasi Pembayaran</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between"><span class="text-slate-500">Metode</span><span class="font-semibold">{{ $transaction->payment_method_label }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Status</span><x-status-badge :variant="$transaction->payment_status_class">{{ $transaction->payment_status_label }}</x-status-badge></div>
                        @if ($transaction->paid_at)
                            <div class="flex justify-between"><span class="text-slate-500">Dibayar</span><span class="font-semibold">{{ $transaction->paid_at->format('d M Y, H:i') }}</span></div>
                        @endif
                    </div>

                    @if (($transaction->payment_status ?? 'pending') === 'pending')
                        <div class="mt-4 space-y-2 rounded-xl bg-amber-50 p-3 text-xs text-amber-700 dark:bg-amber-500/10 dark:text-amber-300" data-payment-countdown data-due-at="{{ optional($transaction->payment_due_at)->toIso8601String() }}">
                            <p>Pesanan Anda sedang menunggu pembayaran online. Silakan selesaikan pembayaran melalui payment yang tersedia agar pesanan diproses. Tekan Pesanan Saya jika ingin membatalkan pesanan.</p>
                            <p class="flex items-center justify-between font-medium">
                                <span>Batas waktu:</span>
                                <span class="font-mono font-bold" data-countdown-display>--:--</span>
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="card-flat p-6 animate-fade-up" style="animation-delay: 240ms">
                    <h2 class="mb-4 text-sm font-bold">Aksi</h2>
                    <div class="space-y-2">
                        <a href="{{ route('checkout.receipt', $transaction) }}" class="btn-outline w-full justify-center text-sm" target="_blank">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Lihat Struk
                        </a>

                        @if ($transaction->isPayable())
                            <button
                                type="button"
                                class="btn-primary w-full justify-center text-sm pay-now-btn"
                                data-pay-url="{{ route('orders.pay', $transaction) }}"
                                data-csrf="{{ csrf_token() }}"
                            >
                                Bayar Sekarang
                            </button>
                        @elseif (in_array($transaction->payment_status, ['cancelled', 'expired']))
                            <div class="rounded-xl bg-rose-50 p-3 text-xs font-semibold text-rose-600 dark:bg-rose-500/10 dark:text-rose-400">
                                {{ $transaction->payment_status === 'expired' ? 'Pembayaran Kedaluwarsa - Pesanan ini dibatalkan karena pembayaran tidak diselesaikan dalam 15 menit.' : 'Pesanan Dibatalkan - Pesanan ini sudah dibatalkan dan tidak dapat dibayar kembali.' }}
                            </div>
                        @endif

                        @if ($transaction->can_be_cancelled)
                            <form
                                id="cancel-form-{{ $transaction->id }}"
                                action="{{ route('orders.cancel', $transaction) }}"
                                method="POST"
                                onsubmit="event.preventDefault(); window.confirmAction('Yakin ingin membatalkan pesanan <strong>{{ $transaction->invoice_number }}</strong>? Stok produk akan dikembalikan.', () => document.getElementById('cancel-form-{{ $transaction->id }}').submit());"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger w-full justify-center text-sm">Batalkan Pesanan</button>
                            </form>
                        @elseif ($transaction->shipping_status === 'diserahkan_ke_kurir')
                            <p class="text-xs font-semibold text-amber-600 dark:text-amber-400">Paket sudah diserahkan kepada kurir dan tidak dapat dibatalkan.</p>
                        @elseif ($transaction->shipping_status === 'dalam_perjalanan')
                            <p class="text-xs font-semibold text-amber-600 dark:text-amber-400">Paket sedang dalam perjalanan dan tidak dapat dibatalkan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ config('midtrans.isProduction') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.clientKey') }}"></script>
<script>
    // Shared Midtrans Snap integration + display-only payment deadline countdown.
    window.initPayNowButtons();
    window.startPaymentCountdown();
</script>
@endpush
