@extends('layouts.app')

@section('title', 'Checkout - ALGO NATION')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="animate-fade-up">
            <p class="text-xs font-bold uppercase tracking-widest text-primary dark:text-primary-soft">Checkout</p>
            <h1 class="mt-1 font-display text-3xl font-extrabold">Selesaikan Pesanan Anda</h1>
        </div>

        <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
            @csrf

            <div class="mt-8 grid gap-8 lg:grid-cols-5">
                {{-- Form fields --}}
                <div class="animate-fade-up delay-150ms space-y-6 lg:col-span-3">
                    {{-- Shipping info --}}
                    <div class="card-flat p-6">
                        <h2 class="mb-5 flex items-center gap-2 font-display text-lg font-bold">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-sm font-bold text-ink">1</span>
                            Alamat Pengiriman
                        </h2>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="label">Nama Lengkap</label>
                                <input type="text" name="full_name" value="{{ auth()->user()->name }}" required class="input" placeholder="Nama penerima">
                            </div>
                            <div>
                                <label class="label">No. Telepon</label>
                                <input type="text" name="phone" required class="input" placeholder="08xxxxxxxxxx">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label">Alamat Lengkap</label>
                                <textarea name="address" rows="3" required class="input" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan"></textarea>
                            </div>
                            <div>
                                <label class="label">Negara</label>
                                <input type="text" name="country" value="{{ old('country', 'Indonesia') }}" required class="input" placeholder="Indonesia">
                            </div>
                            <div>
                                <label class="label">Provinsi / State</label>
                                <input type="text" name="state" class="input" placeholder="Jawa Barat">
                            </div>
                            <div>
                                <label class="label">Kota / Kabupaten</label>
                                <input type="text" name="city" required class="input" placeholder="Contoh: Bandung">
                            </div>
                            <div>
                                <label class="label">Kode Pos</label>
                                <input type="text" name="postal_code" class="input" placeholder="40123">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label">Catatan <span class="text-slate-400">(opsional)</span></label>
                                <textarea name="notes" rows="2" class="input" placeholder="Catatan untuk kurir, misal: varian, jam pengiriman"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Shipping calculation --}}
                    <div class="card-flat p-6">
                        <h2 class="mb-4 flex items-center gap-2 font-display text-lg font-bold">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-sm font-bold text-ink">2</span>
                            Rincian Pengiriman
                        </h2>

                        <button type="button" @click="$store.shipping.estimate()" class="btn-dark btn-sm" :disabled="$store.shipping.loading">
                            <span x-show="!$store.shipping.loading">Hitung Ongkir</span>
                            <span x-show="$store.shipping.loading" class="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"></span>
                        </button>

                        <div x-show="$store.shipping.loaded" x-cloak class="mt-5 grid gap-3 rounded-xl bg-slate-50 p-4 text-sm dark:bg-white/5 sm:grid-cols-2">
                            <p class="flex justify-between gap-3"><span class="text-slate-500">Jenis Pengiriman</span><b x-text="$store.shipping.shipping_type_display" class="capitalize"></b></p>
                            <p class="flex justify-between gap-3"><span class="text-slate-500">Tujuan</span><b x-text="$store.shipping.destination_display"></b></p>
                            <p class="flex justify-between gap-3"><span class="text-slate-500">Layanan</span><b x-text="$store.shipping.result.shipping_courier || '-'"></b></p>
                            <p class="flex justify-between gap-3"><span class="text-slate-500">Jarak</span><b x-text="$store.shipping.formatDistance($store.shipping.result.distance)"></b></p>
                            <p class="flex justify-between gap-3"><span class="text-slate-500">Berat Aktual</span><b x-text="$store.shipping.result.actual_weight + ' kg'"></b></p>
                            <p class="flex justify-between gap-3"><span class="text-slate-500">Berat Volumetrik</span><b x-text="$store.shipping.result.volumetric_weight + ' kg'"></b></p>
                            <p class="flex justify-between gap-3"><span class="text-slate-500">Berat yang Digunakan</span><b x-text="$store.shipping.result.billable_weight + ' kg'"></b></p>
                            <p class="flex justify-between gap-3"><span class="text-slate-500">Zona</span><b x-text="($store.shipping.result.shipping_zone || $store.shipping.result.region || '-')"></b></p>
                        </div>

                        <div x-show="$store.shipping.error" x-cloak class="mt-4 rounded-xl bg-rose-50 p-3 text-sm text-rose-600 dark:bg-rose-500/10" x-text="$store.shipping.error"></div>
                    </div>
                </div>

                {{-- Order summary --}}
                <div class="animate-fade-up delay-300ms lg:col-span-2">
                    <div class="sticky top-24">
                        <div class="card-flat overflow-hidden">
                            <div class="border-b border-slate-100 p-5 dark:border-white/10">
                                <h2 class="font-display text-lg font-bold">Ringkasan Pesanan</h2>
                                <p class="text-xs text-slate-500">{{ count($cart) }} jenis produk</p>
                            </div>

                            <div class="max-h-72 space-y-3 overflow-y-auto p-5">
                                @foreach ($shippingItems as $item)
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item['image_url'] ?? '' }}" alt="{{ $item['name'] }}" class="h-14 w-14 shrink-0 rounded-xl object-cover">
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-sm font-semibold">{{ $item['name'] }}</p>
                                            @if ($item['variant'])
                                                <p class="text-xs text-slate-500">{{ $item['variant'] }}</p>
                                            @endif
                                            <p class="text-xs text-slate-400">Rp {{ number_format($item['price'], 0, ',', '.') }} × {{ $item['quantity'] }}</p>
                                        </div>
                                        <span class="text-sm font-bold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="space-y-2 border-t border-slate-100 p-5 dark:border-white/10">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Subtotal</span>
                                    <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Ongkos Kirim</span>
                                    <span class="font-semibold" x-text="$store.shipping.shipping_ui === null ? '— dihitung saat checkout' : ($store.shipping.shipping_ui === 0 ? 'GRATIS' : formatRupiah($store.shipping.shipping_ui))"></span>
                                </div>
                                <div class="flex justify-between border-t border-dashed border-slate-200 pt-3 dark:border-white/10">
                                    <span class="font-bold">Total</span>
                                    <span class="font-display text-xl font-extrabold text-primary dark:text-primary-soft" x-text="$store.shipping.total_ui === null ? '—' : formatRupiah($store.shipping.total_ui)"></span>
                                </div>

                                <div class="mt-3 rounded-xl bg-slate-50 p-3 text-xs text-slate-600 dark:bg-white/5 dark:text-slate-400">
                                    <p class="flex items-center gap-2"><svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7"/></svg> Klik <b>“Hitung Ongkir”</b> setelah mengisi alamat untuk melihat perkiraan ongkir.</p>
                                    <p class="flex items-center gap-2"><svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2zm3 9h3"/></svg> Pembayaran Online melalui Midtrans (transfer, QRIS, e-wallet, kartu kredit)</p>
                                </div>
                            </div>

                            <div class="p-5 pt-0">
                                <button type="submit" class="btn-primary w-full btn-lg" id="pay-button">
                                    Bayar Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script src="{{ config('midtrans.isProduction') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.clientKey') }}"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('shipping', {
            loading: false,
            loaded: false,
            error: '',
            result: {},
            shipping_ui: null,
            total_ui: null,

            async estimate() {
                const form = document.getElementById('checkout-form');
                if (!form) return;
                const fd = new FormData(form);

                this.loading = true;
                this.error = '';
                try {
                    const res = await axios.post('{{ route('shipping.estimate') }}', fd);
                    this.result = res.data;
                    this.loaded = true;
                    this.shipping_ui = res.data.shipping_cost;
                    this.total_ui = res.data.total;
                } catch (e) {
                    this.error = e.response?.data?.error
                        || Object.values(e.response?.data?.errors || {}).flat().join('\n')
                        || 'Gagal menghitung ongkir. Periksa kembali alamat Anda.';
                } finally {
                    this.loading = false;
                }
            },

            get shipping_type_display() {
                return this.result.shipping_type === 'international' ? 'Internasional' : 'Domestik';
            },
            get destination_display() {
                if (!this.result.destination_city) return '-';
                return this.result.destination_city + ', ' + this.result.destination_country;
            },
            formatDistance(d) {
                const n = Number(d);
                if (d === null || d === undefined || isNaN(n)) return '-';
                // Truncate (never round up) to one decimal so 100,1 km stays
                // 100,1 and never shows as 100,2.
                const truncated = Math.floor(n * 10) / 10;
                return truncated.toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + ' km';
            },
        });
    });

    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const btn = document.getElementById('pay-button');
        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Memproses...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: new FormData(form),
        })
        .then(res => res.json())
        .then(data => {
            if (data.error || data.errors) {
                const msg = data.error || Object.values(data.errors).flat().join('\n');
                alert(msg);
                btn.disabled = false;
                btn.textContent = originalText;
                return;
            }

            // Snap popup callbacks:
            // - onSuccess  -> actively verify + finalize the payment server-side
            //   (the webhook is often delayed/unreachable in local development),
            //   then go to the receipt which already shows "Pembayaran Berhasil".
            // - onPending / onClose (user clicks X or cancels) -> the order stays
            //   "Menunggu Proses Pembayaran" until the gateway confirms it.
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    fetch(data.finalize_url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(result),
                    })
                    .then(r => r.json())
                    .then(() => { window.location.href = data.redirect_url; })
                    .catch(() => { window.location.href = data.redirect_url; });
                },
                onPending: function(result) {
                    window.location.href = data.redirect_url;
                },
                onError: function(result) {
                    alert('Pembayaran gagal. Silakan coba lagi dari halaman Pesanan Saya.');
                    btn.disabled = false;
                    btn.textContent = originalText;
                },
                onClose: function() {
                    window.location.href = data.redirect_url;
                },
            });
        })
        .catch(() => {
            alert('Terjadi kesalahan. Silakan coba lagi.');
            btn.disabled = false;
            btn.textContent = originalText;
        });
    });
</script>
@endpush