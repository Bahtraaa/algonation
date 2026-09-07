@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $transaction->id)
@section('page-title', 'Detail Pesanan #' . $transaction->id)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Pesanan
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Left: Order Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Summary --}}
            <div class="card-flat p-6 animate-fade-up">
                <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4 dark:border-white/10">
                    <div>
                        <h2 class="font-display text-lg font-bold">Pesanan #{{ $transaction->id }}</h2>
                        <p class="text-xs text-slate-500">{{ $transaction->midtrans_order_id }}</p>
                        <p class="text-xs text-slate-500">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <x-status-badge :variant="$transaction->payment_status_class">{{ $transaction->payment_status_label }}</x-status-badge>
                        <x-status-badge :variant="$transaction->shipping_status_class">{{ $transaction->shipping_status_label }}</x-status-badge>
                    </div>
                </div>

                <div class="mt-4">
                    <h3 class="mb-3 text-xs font-bold uppercase tracking-widest text-slate-400">Item Pesanan</h3>
                    <div class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($transaction->details as $detail)
                            <div class="flex items-center gap-4 py-3">
                                <img src="{{ $detail->product?->image_url ?? 'https://placehold.co/100' }}" alt="" class="h-14 w-14 rounded-xl object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold">{{ $detail->product?->name ?? 'Produk' }}</p>
                                    @if ($detail->variant)
                                        <p class="text-xs text-slate-500">{{ $detail->variant->name }}</p>
                                    @endif
                                    <p class="text-xs text-slate-500">{{ $detail->quantity }} x Rp {{ number_format($detail->subtotal / max($detail->quantity, 1), 0, ',', '.') }}</p>
                                </div>
                                <span class="text-sm font-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 border-t border-slate-100 pt-4 dark:border-white/10">
                    <div class="flex justify-between text-sm"><span class="text-slate-500">Subtotal Produk</span><span class="font-semibold">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-slate-500">Ongkos Kirim</span><span class="font-semibold">{{ $transaction->shipping_cost > 0 ? 'Rp ' . number_format($transaction->shipping_cost, 0, ',', '.') : 'Gratis' }}</span></div>
                    <div class="flex justify-between text-base font-bold mt-2"><span>Total</span><span class="text-primary dark:text-primary-soft">Rp {{ number_format($transaction->total_price + $transaction->shipping_cost, 0, ',', '.') }}</span></div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="card-flat p-6 animate-fade-up" style="animation-delay: 60ms">
                <h3 class="mb-3 text-sm font-bold">Alamat Pengiriman</h3>
                <p class="whitespace-pre-line text-sm text-slate-600 dark:text-slate-300">{{ $transaction->shipping_address }}</p>
            </div>
        </div>

        {{-- Right: Shipping & Actions --}}
        <div class="space-y-6">
            {{-- Shipping Status Timeline --}}
            <div class="card-flat p-6 animate-fade-up" style="animation-delay: 120ms">
                <h3 class="mb-4 text-sm font-bold">Status Pengiriman</h3>
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
                            $isCompleted = $currentIdx >= 0 && $idx <= $currentIdx;
                            $isActive = $idx === $currentIdx && $currentIdx >= 0;
                        @endphp
                        <div class="flex items-start gap-3">
                            <div class="flex flex-col items-center">
                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[10px] font-bold
                                    {{ $isActive ? 'bg-primary text-ink ring-4 ring-primary/20' : ($isCompleted ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-400 dark:bg-white/10 dark:text-slate-500') }}">
                                    @if ($isCompleted && !$isActive)
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        {{ $idx + 1 }}
                                    @endif
                                </div>
                                @if (!$loop->last)
                                    <div class="h-6 w-0.5 {{ $isCompleted ? 'bg-emerald-400' : 'bg-slate-200 dark:bg-white/10' }}"></div>
                                @endif
                            </div>
                            <div class="pt-0.5">
                                <p class="text-xs font-semibold {{ $isActive ? 'text-primary dark:text-primary-soft' : ($isCompleted ? 'text-slate-700 dark:text-slate-200' : 'text-slate-400') }}">{{ $stepLabels[$step] }}</p>
                                @if ($isActive)
                                    <p class="text-[10px] text-primary/70">Saat ini</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($transaction->shipping_status === 'pengiriman_gagal')
                    <div class="mt-3 rounded-lg bg-rose-50 p-3 text-xs font-semibold text-rose-600 dark:bg-rose-500/10 dark:text-rose-400">Pengiriman Gagal</div>
                @endif
                @if ($transaction->shipping_status === 'dibatalkan')
                    <div class="mt-3 rounded-lg bg-rose-50 p-3 text-xs font-semibold text-rose-600 dark:bg-rose-500/10 dark:text-rose-400">Pesanan Dibatalkan</div>
                @endif
            </div>

            {{-- Edit Shipping Form --}}
            <div class="card-flat p-6 animate-fade-up" style="animation-delay: 180ms" x-data="editShippingForm()">
                <h3 class="mb-4 text-sm font-bold">Edit Pengiriman</h3>

                <div x-show="error" x-text="error" class="mb-3 rounded-lg bg-rose-50 p-3 text-xs text-rose-600 dark:bg-rose-500/10 dark:text-rose-400"></div>
                <div x-show="success" x-text="success" class="mb-3 rounded-lg bg-emerald-50 p-3 text-xs text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400"></div>

                <form @submit.prevent="submit()" class="space-y-3">
                    <div>
                        <label class="mb-1 block text-xs font-semibold">Status Pengiriman</label>
                        <select x-model="form.shipping_status" class="input w-full text-xs">
                            @foreach ($shippingStatuses as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold">Kurir</label>
                        <select x-model="form.shipping_courier" class="input w-full text-xs">
                            <option value="">Pilih Kurir</option>
                            <option value="JNE">JNE</option>
                            <option value="J&T">J&T</option>
                            <option value="SiCepat">SiCepat</option>
                            <option value="Tiki">Tiki</option>
                            <option value="POS">POS Indonesia</option>
                            <option value="AnterAja">AnterAja</option>
                            <option value="GoSend">GoSend</option>
                            <option value="GrabExpress">GrabExpress</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold">No. Resi</label>
                        <input type="text" x-model="form.tracking_number" class="input w-full text-xs" placeholder="JNE123456789">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold">Estimasi Mulai</label>
                            <input type="date" x-model="form.estimated_delivery_start" class="input w-full text-xs">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold">Estimasi Selesai</label>
                            <input type="date" x-model="form.estimated_delivery_end" class="input w-full text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold">Tanggal Kirim</label>
                        <input type="date" x-model="form.shipped_at" class="input w-full text-xs">
                    </div>
                    <button type="submit" :disabled="saving" class="btn-primary w-full text-sm">
                        <span x-show="!saving">Simpan Perubahan</span>
                        <span x-show="saving">Menyimpan...</span>
                    </button>
                </form>
            </div>

            {{-- Shipping Info --}}
            <div class="card-flat p-6 animate-fade-up" style="animation-delay: 240ms">
                <h3 class="mb-3 text-sm font-bold">Info Pengiriman</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between"><span class="text-slate-500">Kurir</span><span class="font-semibold">{{ $transaction->shipping_courier ?? '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">No. Resi</span><span class="font-mono font-semibold">{{ $transaction->tracking_number ?? '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Dikirim</span><span class="font-semibold">{{ $transaction->shipped_at?->format('d M Y') ?? '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Estimasi Tiba</span><span class="font-semibold">{{ $transaction->estimated_delivery }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Status</span><x-status-badge :variant="$transaction->shipping_status_class">{{ $transaction->shipping_status_label }}</x-status-badge></div>
                    @if ($transaction->delivered_at)
                        <div class="flex justify-between"><span class="text-slate-500">Diterima</span><span class="font-semibold text-emerald-600">{{ $transaction->delivered_at->format('d M Y, H:i') }}</span></div>
                    @endif
                    @if ($transaction->shipping_updated_at)
                        <div class="flex justify-between"><span class="text-slate-500">Terakhir Update</span><span class="text-slate-400">{{ $transaction->shipping_updated_at->format('d M Y, H:i') }}</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function editShippingForm() {
    return {
        form: {
            shipping_status: '{{ $transaction->shipping_status }}',
            shipping_courier: '{{ addslashes($transaction->shipping_courier ?? '') }}',
            tracking_number: '{{ addslashes($transaction->tracking_number ?? '') }}',
            estimated_delivery_start: '{{ $transaction->estimated_delivery_start?->format('Y-m-d') ?? '' }}',
            estimated_delivery_end: '{{ $transaction->estimated_delivery_end?->format('Y-m-d') ?? '' }}',
            shipped_at: '{{ $transaction->shipped_at?->format('Y-m-d') ?? '' }}',
        },
        error: '',
        success: '',
        saving: false,

        async submit() {
            this.error = '';
            this.success = '';
            this.saving = true;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const res = await fetch('/admin/orders/{{ $transaction->id }}/shipping', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.form),
                });

                const data = await res.json();

                if (!res.ok) {
                    this.error = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message || 'Terjadi kesalahan.');
                    return;
                }

                this.success = data.message;
                setTimeout(() => window.location.reload(), 800);
            } catch (e) {
                this.error = 'Terjadi kesalahan jaringan.';
            } finally {
                this.saving = false;
            }
        }
    };
}
</script>
@endpush
