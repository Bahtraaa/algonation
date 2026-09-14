@extends('layouts.app')

@section('title', 'Checkout - ALGO NATION')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8" x-data="checkoutAddress()">
        <div class="animate-fade-up">
            <p class="text-xs font-bold uppercase tracking-widest text-primary dark:text-primary-soft">Checkout</p>
            <h1 class="mt-1 font-display text-3xl font-extrabold">Selesaikan Pesanan Anda</h1>
        </div>

        <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
            @csrf
            <input type="hidden" name="address_id" :value="selectedId">

            <div class="mt-8 grid gap-8 lg:grid-cols-5">
                {{-- Address selection --}}
                <div class="animate-fade-up delay-150ms space-y-6 lg:col-span-3">
                    <div class="card-flat p-6">
                        <h2 class="mb-5 flex items-center gap-2 font-display text-lg font-bold">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-sm font-bold text-ink">1</span>
                            Alamat Pengiriman
                        </h2>

                        @if ($addresses->isEmpty())
                            <div class="rounded-xl bg-amber-50 p-4 text-sm text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                                <p class="font-semibold">Silakan tambahkan alamat pengiriman terlebih dahulu.</p>
                                <p class="mt-1">Anda belum menyimpan alamat. Tambahkan sekali, lalu checkout tanpa mengetik ulang.</p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <a href="{{ route('addresses.create', ['return_to' => 'checkout']) }}" class="btn-primary btn-sm">+ Tambah Alamat</a>
                                    <a href="{{ route('addresses.index') }}" class="btn-outline btn-sm">Kelola Alamat Saya</a>
                                </div>
                            </div>
                        @else
                            {{-- Selected address card --}}
                            <template x-for="addr in addresses" :key="addr.id">
                                <div x-show="addr.id === selectedId" class="rounded-xl border border-slate-200 p-4 dark:border-white/10">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold dark:bg-white/10" x-text="addr.label"></span>
                                        <span x-show="addr.is_default" class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300">[Alamat Utama]</span>
                                    </div>
                                    <p class="mt-3 text-sm font-bold" x-text="addr.recipient_name"></p>
                                    <p class="text-sm text-slate-500" x-text="addr.phone"></p>
                                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300" x-text="addr.address"></p>
                                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300" x-text="addr.district + ', ' + addr.city"></p>
                                    <p class="text-sm text-slate-600 dark:text-slate-300" x-text="addr.province + ', ' + addr.postal_code"></p>
                                    <p class="text-sm text-slate-500" x-text="addr.country"></p>
                                    <p x-show="addr.note" class="mt-1 text-xs text-slate-400" x-text="'Catatan: ' + addr.note"></p>
                                </div>
                            </template>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <button type="button" @click="showList = !showList" class="btn-outline btn-sm">
                                    <span x-text="showList ? 'Tutup Daftar' : '[ Ubah Alamat ]'"></span>
                                </button>
                                <button type="button" @click="showAddModal = true" class="btn-ghost btn-sm">+ Tambah Alamat Baru</button>
                            </div>

                            {{-- Address list --}}
                            <div x-show="showList" x-cloak class="mt-4 space-y-3">
                                <template x-for="addr in addresses" :key="'list-' + addr.id">
                                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition-all" :class="addr.id === selectedId ? 'border-primary bg-primary/5' : 'border-slate-200 dark:border-white/10 hover:border-primary/50'">
                                        <input type="radio" name="address_choice" :value="addr.id" x-model.number="selectedId" @change="onSelect(addr.id)" class="mt-1 h-4 w-4 accent-[#1b1b18]">
                                        <span class="flex-1">
                                            <span class="flex flex-wrap items-center gap-2">
                                                <b class="text-sm" x-text="addr.label"></b>
                                                <span x-show="addr.is_default" class="rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-bold text-emerald-700">[Alamat Utama]</span>
                                            </span>
                                            <span class="mt-1 block text-sm font-semibold" x-text="addr.recipient_name + ' — ' + addr.phone"></span>
                                            <span class="mt-0.5 block text-sm text-slate-500" x-text="addr.address + ', ' + addr.district + ', ' + addr.city + ' ' + addr.postal_code"></span>
                                        </span>
                                    </label>
                                </template>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('addresses.index') }}" class="btn-ghost btn-sm">Kelola di Alamat Saya →</a>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Shipping calculation --}}
                    <div class="card-flat p-6">
                        <h2 class="mb-4 flex items-center gap-2 font-display text-lg font-bold">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-sm font-bold text-ink">2</span>
                            Rincian Pengiriman
                        </h2>

                        @if ($addresses->isEmpty())
                            <p class="text-sm text-slate-500">Ongkir akan dihitung otomatis setelah Anda menambahkan alamat.</p>
                        @else
                            <button type="button" @click="$store.shipping.estimate()" class="btn-dark btn-sm" :disabled="$store.shipping.loading">
                                <span x-show="!$store.shipping.loading">Hitung Ongkir</span>
                                <span x-show="$store.shipping.loading" class="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"></span>
                            </button>
                            <p class="mt-2 text-xs text-slate-400">Ongkir dihitung dari alamat yang dipilih dan diperbarui otomatis saat Anda mengganti alamat.</p>

                            <div x-show="$store.shipping.loaded" x-cloak class="mt-5 grid gap-3 rounded-xl bg-slate-50 p-4 text-sm dark:bg-white/5 sm:grid-cols-2">
                                <p class="flex justify-between gap-3"><span class="text-slate-500">Jenis Pengiriman</span><b x-text="$store.shipping.shipping_type_display" class="capitalize"></b></p>
                                <p class="flex justify-between gap-3"><span class="text-slate-500">Tujuan</span><b x-text="$store.shipping.destination_display"></b></p>
                                <p class="flex justify-between gap-3"><span class="text-slate-500">Layanan</span><b x-text="$store.shipping.result.shipping_courier || '-'"></b></p>
                                <p class="flex justify-between gap-3"><span class="text-slate-500">Jarak</span><b x-text="$store.shipping.formatDistance($store.shipping.result.distance, $store.shipping.result.distance_estimated)"></b></p>
                                <p class="flex justify-between gap-3"><span class="text-slate-500">Berat Aktual</span><b x-text="$store.shipping.result.actual_weight + ' kg'"></b></p>
                                <p class="flex justify-between gap-3"><span class="text-slate-500">Berat Volumetrik</span><b x-text="$store.shipping.result.volumetric_weight + ' kg'"></b></p>
                                <p class="flex justify-between gap-3"><span class="text-slate-500">Berat yang Digunakan</span><b x-text="$store.shipping.result.billable_weight + ' kg'"></b></p>
                                <p class="flex justify-between gap-3"><span class="text-slate-500">Zona</span><b x-text="($store.shipping.result.shipping_zone || $store.shipping.result.region || '-')"></b></p>
                            </div>

                            <div x-show="$store.shipping.error" x-cloak class="mt-4 rounded-xl bg-rose-50 p-3 text-sm text-rose-600 dark:bg-rose-500/10" x-text="$store.shipping.error"></div>
                        @endif
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
                                    <p class="flex items-center gap-2"><svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7"/></svg> Alamat default langsung dipilih — cukup checkout tanpa mengisi alamat lagi.</p>
                                    <p class="flex items-center gap-2"><svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2zm3 9h3"/></svg> Pembayaran Online melalui Midtrans (transfer, QRIS, e-wallet, kartu kredit)</p>
                                </div>
                            </div>

                            <div class="p-5 pt-0">
                                @if ($addresses->isEmpty())
                                    <a href="{{ route('addresses.create', ['return_to' => 'checkout']) }}" class="btn-primary w-full btn-lg justify-center">Tambah Alamat</a>
                                @else
                                    <button type="submit" class="btn-primary w-full btn-lg" id="pay-button">
                                        Bayar Sekarang
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Modal tambah alamat cepat (tanpa keluar dari checkout) --}}
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-70 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
            <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-900" @click.outside="showAddModal = false">
                <h3 class="font-display text-lg font-bold">Tambah Alamat Baru</h3>
                <p class="mt-1 text-xs text-slate-500">Alamat baru langsung dipilih untuk pesanan ini.</p>
                <div x-show="addError" x-text="addError" class="mt-3 rounded-xl bg-rose-50 p-3 text-sm text-rose-600"></div>
                <form @submit.prevent="submitNewAddress()" class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label">Label</label>
                        <input type="text" x-model="newAddress.label" class="input" placeholder="Rumah / Kantor" required>
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" x-model="newAddress.is_default" class="h-4 w-4"> Jadikan alamat utama</label>
                    </div>
                    <div>
                        <label class="label">Nama Penerima</label>
                        <input type="text" x-model="newAddress.recipient_name" class="input" required>
                    </div>
                    <div>
                        <label class="label">No. WhatsApp</label>
                        <input type="text" x-model="newAddress.phone" class="input" placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div>
                        <label class="label">Negara</label>
                        <input type="text" x-model="newAddress.country" class="input" required>
                    </div>
                    <div>
                        <label class="label">Provinsi</label>
                        <input type="text" x-model="newAddress.province" class="input" required>
                    </div>
                    <div>
                        <label class="label">Kota / Kabupaten</label>
                        <input type="text" x-model="newAddress.city" class="input" required>
                    </div>
                    <div>
                        <label class="label">Kecamatan</label>
                        <input type="text" x-model="newAddress.district" class="input" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="label">Kode Pos</label>
                        <input type="text" x-model="newAddress.postal_code" class="input" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="label">Alamat Lengkap</label>
                        <textarea x-model="newAddress.address" rows="3" class="input" required></textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="label">Catatan (opsional)</label>
                        <textarea x-model="newAddress.note" rows="2" class="input"></textarea>
                    </div>
                    <div class="flex gap-3 sm:col-span-2">
                        <button type="button" @click="showAddModal = false" class="btn-outline flex-1 justify-center">Batal</button>
                        <button type="submit" :disabled="adding" class="btn-primary flex-1 justify-center"><span x-text="adding ? 'Menyimpan...' : 'Simpan & Pilih'"></span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ config('midtrans.isProduction') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.clientKey') }}"></script>
<script>
    function checkoutAddress() {
        return {
            addresses: @json($addresses->values()),
            selectedId: {{ $selectedAddress?->id ?? 'null' }},
            showList: false,
            showAddModal: false,
            adding: false,
            addError: '',
            newAddress: { label: 'Rumah', is_default: false, recipient_name: '{{ addslashes(auth()->user()->name ?? '') }}', phone: '', country: 'Indonesia', province: '', city: '', district: '', postal_code: '', address: '', note: '' },

            init() {
                // Otomatis hitung ongkir dari alamat default saat halaman dibuka.
                if (this.selectedId) {
                    setTimeout(() => this.$store.shipping.estimate(), 300);
                }
            },

            onSelect(id) {
                this.selectedId = id;
                this.showList = false;
                this.$store.shipping.estimate();
                // Simpan pilihan ke URL agar refresh / tambah alamat tidak hilang.
                const url = new URL(window.location.href);
                url.searchParams.set('address_id', id);
                window.history.replaceState({}, '', url);
            },

            async submitNewAddress() {
                this.adding = true;
                this.addError = '';
                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await axios.post('{{ route('addresses.store') }}', this.newAddress, {
                        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                    });
                    const addr = res.data.address;
                    this.addresses.push(addr);
                    this.selectedId = addr.id;
                    this.showAddModal = false;
                    this.$store.shipping.estimate();
                    window.toast('Alamat baru ditambahkan dan dipilih.', 'success');
                } catch (e) {
                    this.addError = e.response?.data?.message
                        || Object.values(e.response?.data?.errors || {}).flat().join('\n')
                        || 'Gagal menyimpan alamat. Periksa kembali isian Anda.';
                } finally {
                    this.adding = false;
                }
            },
        };
    }

    document.addEventListener('alpine:init', () => {
        Alpine.store('shipping', {
            loading: false,
            loaded: false,
            error: '',
            result: {},
            shipping_ui: null,
            total_ui: null,

            selectedAddressId() {
                const input = document.querySelector('#checkout-form input[name="address_id"]');
                return input ? input.value : null;
            },

            async estimate() {
                const addressId = this.selectedAddressId();
                if (!addressId) {
                    this.error = 'Silakan pilih alamat pengiriman terlebih dahulu.';
                    return;
                }

                this.loading = true;
                this.error = '';
                try {
                    const res = await axios.post('{{ route('shipping.estimate') }}', { address_id: addressId });
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
            formatDistance(d, estimated = false) {
                const n = Number(d);
                if (d === null || d === undefined || isNaN(n)) return '-';
                const truncated = Math.floor(n * 10) / 10;
                const label = truncated.toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + ' km';
                // "~" = titik koordinat kota tak ditemukan sehingga memakai
                // titik tengah negara (tetap akurat untuk orde ribuan km).
                return (estimated ? '~' : '') + label;
            },
        });
    });

    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const btn = document.getElementById('pay-button');
        if (!btn) return;
        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Memproses...';

        const addressId = form.querySelector('input[name="address_id"]').value;
        if (!addressId) {
            alert('Silakan tambahkan alamat pengiriman terlebih dahulu.');
            btn.disabled = false;
            btn.textContent = originalText;
            return;
        }

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ address_id: Number(addressId) }),
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
