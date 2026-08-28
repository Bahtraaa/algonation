@extends('layouts.app')

@section('title', 'Checkout — ALGO NATION')

@section('content')
    @php
        $subtotal = array_sum(array_map(fn ($i) => $i['price'] * $i['quantity'], $cart));
        $shipping = \App\Http\Controllers\CartController::shippingCost($subtotal);
        $total = $subtotal + $shipping;
    @endphp

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
                                @foreach ($cart as $item)
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="h-14 w-14 shrink-0 rounded-xl object-cover">
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
                                    <span class="font-semibold {{ $shipping === 0 ? 'text-primary' : '' }}">
                                        {{ $shipping === 0 ? 'GRATIS' : 'Rp '.number_format($shipping, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between border-t border-dashed border-slate-200 pt-3 dark:border-white/10">
                                    <span class="font-bold">Total</span>
                                    <span class="font-display text-xl font-extrabold text-primary dark:text-primary-soft">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>

                                <div class="mt-3 rounded-xl bg-slate-50 p-3 text-xs text-slate-600 dark:bg-white/5 dark:text-slate-400">
                                    <p>📦 Estimasi pengiriman: <span class="font-semibold">{{ \App\Http\Controllers\CartController::estimatedDays() }} hari kerja</span></p>
                                    <p>💰 Pembayaran dilakukan saat barang tiba (COD)</p>
                                </div>
                            </div>

                            <div class="p-5 pt-0">
                                <button type="submit" class="btn-primary w-full btn-lg">
                                    Buat Pesanan — Rp {{ number_format($total, 0, ',', '.') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

