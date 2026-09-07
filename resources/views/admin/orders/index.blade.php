@extends('layouts.admin')

@section('title', 'Pesanan & Pengiriman')
@section('page-title', 'Pesanan & Pengiriman')

@section('content')
    {{-- Filters --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-1 flex-col gap-3 sm:flex-row">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="relative flex-1 sm:max-w-xs">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID, nama, resi..."
                    class="input pl-10">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
            <form action="{{ route('admin.orders.index') }}" method="GET" class="flex gap-3">
                <select name="shipping_status" onchange="this.form.submit()" class="input sm:w-48">
                    <option value="all">Semua Status</option>
                    @foreach ($shippingStatuses as $key => $label)
                        <option value="{{ $key }}" {{ request('shipping_status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="payment_status" onchange="this.form.submit()" class="input sm:w-44">
                    <option value="all">Semua Pembayaran</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                    <option value="expired" {{ request('payment_status') === 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
                    <option value="cancelled" {{ request('payment_status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                @if (request('search') || request('shipping_status') || request('payment_status'))
                    <a href="{{ route('admin.orders.index') }}" class="btn-ghost btn-sm whitespace-nowrap">Reset</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-wrap animate-fade-up">
        <table class="table-base">
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>User</th>
                    <th>Produk</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Kurir</th>
                    <th>Resi</th>
                    <th>Status Kirim</th>
                    <th>Estimasi</th>
                    <th>Tanggal</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $t)
                    <tr>
                        <td>
                            <p class="font-semibold text-xs">#{{ $t->id }}</p>
                            <p class="text-[10px] text-slate-400">{{ $t->midtrans_order_id }}</p>
                        </td>
                        <td>
                            <p class="truncate text-xs font-medium">{{ $t->user?->name ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400">{{ $t->user?->email }}</p>
                        </td>
                        <td>
                            @foreach ($t->details->take(2) as $d)
                                <p class="truncate text-xs">{{ $d->product?->name ?? 'Produk' }} x{{ $d->quantity }}</p>
                            @endforeach
                            @if ($t->details->count() > 2)
                                <p class="text-[10px] text-slate-400">+{{ $t->details->count() - 2 }} lagi</p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap">
                            <span class="text-xs font-bold">Rp {{ number_format($t->total_price + $t->shipping_cost, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            <x-status-badge small :variant="$t->payment_status_class">{{ $t->payment_status_label }}</x-status-badge>
                        </td>
                        <td>
                            <span class="text-xs">{{ $t->shipping_courier ?? '-' }}</span>
                        </td>
                        <td>
                            @if ($t->tracking_number)
                                <span class="text-xs font-mono">{{ $t->tracking_number }}</span>
                            @else
                                <span class="text-xs text-slate-400">-</span>
                            @endif
                        </td>
                        <td>
                            <x-status-badge small :variant="$t->shipping_status_class">{{ $t->shipping_status_label }}</x-status-badge>
                        </td>
                        <td class="whitespace-nowrap">
                            @if ($t->estimated_delivery_start && $t->estimated_delivery_end)
                                <span class="text-[10px]">{{ $t->estimated_delivery_start->format('j') }}-{{ $t->estimated_delivery_end->format('j M Y') }}</span>
                            @else
                                <span class="text-[10px] text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap text-xs text-slate-500">{{ $t->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.orders.show', $t) }}" class="btn-ghost btn-sm text-xs" title="Lihat Detail">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <button @click="
                                    $dispatch('open-edit-modal', {
                                        id: {{ $t->id }},
                                        shipping_courier: '{{ addslashes($t->shipping_courier ?? '') }}',
                                        tracking_number: '{{ addslashes($t->tracking_number ?? '') }}',
                                        shipping_status: '{{ $t->shipping_status }}',
                                        estimated_delivery_start: '{{ $t->estimated_delivery_start?->format('Y-m-d') ?? '' }}',
                                        estimated_delivery_end: '{{ $t->estimated_delivery_end?->format('Y-m-d') ?? '' }}',
                                        shipped_at: '{{ $t->shipped_at?->format('Y-m-d') ?? '' }}',
                                    });
                                " class="btn-ghost btn-sm text-xs" title="Edit Pengiriman">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="py-16 text-center">
                            <p class="font-semibold">Tidak ada pesanan.</p>
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

    {{-- Edit Shipping Modal --}}
    <div x-data="editShippingModal()" x-on:open-edit-modal.window="openWith($event.detail)" x-cloak x-show="open" x-transition.opacity
        style="display: none;"
        class="fixed inset-0 z-70 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-lg animate-scale-in rounded-2xl border border-white/10 bg-white p-6 shadow-2xl dark:bg-slate-900" @click.outside="closeModal()">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="font-display text-lg font-bold">Edit Pengiriman</h3>
                <button @click="closeModal()" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div x-show="error" x-text="error" class="mb-4 rounded-lg bg-rose-50 p-3 text-sm text-rose-600 dark:bg-rose-500/10 dark:text-rose-400"></div>
            <div x-show="success" x-text="success" class="mb-4 rounded-lg bg-emerald-50 p-3 text-sm text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400"></div>

            <form @submit.prevent="submitForm()" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-semibold">Kurir</label>
                        <select x-model="form.shipping_courier" class="input w-full">
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
                        <input type="text" x-model="form.tracking_number" class="input w-full" placeholder="JNE123456789">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold">Status Pengiriman</label>
                    <select x-model="form.shipping_status" class="input w-full" required>
                        @foreach ($shippingStatuses as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-semibold">Estimasi Mulai</label>
                        <input type="date" x-model="form.estimated_delivery_start" class="input w-full">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold">Estimasi Selesai</label>
                        <input type="date" x-model="form.estimated_delivery_end" class="input w-full">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold">Tanggal Kirim</label>
                    <input type="date" x-model="form.shipped_at" class="input w-full">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="closeModal()" class="btn-outline flex-1">Batal</button>
                    <button type="submit" :disabled="saving" class="btn-primary flex-1">
                        <span x-show="!saving">Simpan Perubahan</span>
                        <span x-show="saving" class="flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function editShippingModal() {
    return {
        open: false,
        form: {
            shipping_courier: '',
            tracking_number: '',
            shipping_status: 'menunggu_diproses',
            estimated_delivery_start: '',
            estimated_delivery_end: '',
            shipped_at: '',
        },
        error: '',
        success: '',
        saving: false,
        editTransactionId: null,

        openWith(t) {
            if (!t) return;
            this.open = true;
            this.editTransactionId = t.id;
            this.form.shipping_courier = t.shipping_courier;
            this.form.tracking_number = t.tracking_number;
            this.form.shipping_status = t.shipping_status;
            this.form.estimated_delivery_start = t.estimated_delivery_start;
            this.form.estimated_delivery_end = t.estimated_delivery_end;
            this.form.shipped_at = t.shipped_at;
            this.error = '';
            this.success = '';
        },

        closeModal() {
            this.open = false;
            this.error = '';
            this.success = '';
        },

        async submitForm() {
            this.error = '';
            this.success = '';
            this.saving = true;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const res = await fetch(`/admin/orders/${this.editTransactionId}/shipping`, {
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
