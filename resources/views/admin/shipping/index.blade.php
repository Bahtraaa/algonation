@extends('layouts.admin')

@section('title', 'Pengaturan Ongkir')
@section('page-title', 'Pengaturan Pengiriman')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8">
        @if (session('success'))<div class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="rounded-xl bg-rose-50 p-4 text-sm text-rose-600 dark:bg-rose-500/10">{{ session('error') }}</div>@endif

        {{-- Origin settings --}}
        <div class="card-flat p-6">
            <h2 class="font-display text-lg font-bold">Asal Toko / Koordinat</h2>
            <p class="mt-1 text-xs text-slate-500">Negara asal menentukan otomatis pengiriman Domestik atau Internasional berdasarkan negara tujuan checkout.</p>
            <form method="POST" action="{{ route('admin.shipping.settings.update') }}" class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="label">Negara Asal</label>
                    <input type="text" name="origin_country" value="{{ old('origin_country', $settings->origin_country ?? 'Indonesia') }}" required class="input">
                </div>
                <div>
                    <label class="label">Kota Asal</label>
                    <input type="text" name="origin_city" value="{{ old('origin_city', $settings->origin_city ?? '') }}" class="input">
                </div>
                <div>
                    <label class="label">Latitude Asal</label>
                    <input type="number" step="any" name="origin_latitude" value="{{ old('origin_latitude', $settings->origin_latitude ?? '') }}" class="input" placeholder="-6.200000">
                </div>
                <div>
                    <label class="label">Longitude Asal</label>
                    <input type="number" step="any" name="origin_longitude" value="{{ old('origin_longitude', $settings->origin_longitude ?? '') }}" class="input" placeholder="106.816666">
                </div>
                <div>
                    <label class="label">Routing Provider</label>
                    <select name="routing_provider" class="input">
                        <option value="none" {{ ($settings->routing_provider ?? 'none') === 'none' ? 'selected' : '' }}>Tidak ada (Haversine)</option>
                        <option value="osrm" {{ ($settings->routing_provider ?? '') === 'osrm' ? 'selected' : '' }}>OSRM (road distance)</option>
                    </select>
                </div>
                <label class="flex items-center gap-2 text-sm font-semibold text-slate-600">
                    <input type="checkbox" name="enable_routing" value="1" {{ ($settings->enable_routing ?? false) ? 'checked' : '' }} class="h-4 w-4 accent-primary">
                    Gunakan routing road distance
                </label>
                <button class="btn-primary self-end lg:col-span-2">Simpan Pengaturan</button>
            </form>
        </div>

        {{-- Domestic zones --}}
        <div class="card-flat p-6">
            <h2 class="font-display text-lg font-bold">Zona Pengiriman Domestik</h2>
            <p class="mt-1 text-xs text-slate-500">Ongkir domestik = billable_weight (kg) × tarif zona. Batas jarak & tarif bisa diubah kapan saja tanpa mengubah kode.</p>

            <form method="POST" action="{{ route('admin.shipping.zones.store') }}" class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-6">
                @csrf
                <input type="text" name="name" placeholder="Nama zona (contoh: Zona 1)" required class="input lg:col-span-2">
                <input type="number" name="min_distance_km" min="0" value="0" placeholder="Min jarak (km)" required class="input">
                <input type="number" name="max_distance_km" min="1" placeholder="Max jarak (km)" class="input">
                <input type="number" name="rate_per_kg" min="0" step="0.01" value="10000" placeholder="Tarif / kg" required class="input">
                <button class="btn-primary">Tambah Zona</button>
                <input type="number" name="min_charge" min="0" step="0.01" value="0" placeholder="Min charge" class="input lg:col-span-2">
                <label class="flex items-center gap-2 text-sm font-semibold text-slate-600 lg:col-span-2">
                    <input type="checkbox" name="active" value="1" checked class="h-4 w-4 accent-primary"> Aktif
                </label>
            </form>

            <div class="table-wrap mt-5">
                <table class="table-base">
                    <thead><tr><th>Nama</th><th>Jarak</th><th>Tarif / kg</th><th>Min Charge</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
                    <tbody>
                        @forelse ($zones as $zone)
                            <tr>
                                <td class="font-semibold">{{ $zone->name }}</td>
                                <td>{{ $zone->min_distance_km }}–{{ $zone->max_distance_km ?? '∞' }} km</td>
                                <td>Rp {{ number_format($zone->rate_per_kg, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($zone->min_charge, 0, ',', '.') }}</td>
                                <td>{{ $zone->active ? 'Aktif' : 'Nonaktif' }}</td>
                                <td>
                                    <div x-data="{ open: false }" @keydown.escape.window="open = false" class="flex justify-end gap-1">
                                        <button type="button" class="btn-ghost btn-sm" @click="open = true">Edit</button>
                                        <template x-teleport="body">
                                            <div x-show="open" x-transition.opacity x-cloak class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm" @click="open = false">
                                                <div class="max-h-[90vh] w-full max-w-md overflow-y-auto rounded-2xl border border-slate-100 bg-white p-5 shadow-2xl dark:border-white/10 dark:bg-slate-900" @click.stop>
                                                    <h4 class="mb-4 text-sm font-bold text-slate-700 dark:text-slate-200">Edit Zona</h4>
                                                    <form method="POST" action="{{ route('admin.shipping.zones.update', $zone) }}" class="space-y-3.5">
                                                        @csrf
                                                        @method('PUT')
                                                        <div>
                                                            <label class="mb-1 block text-xs font-semibold text-slate-500">Nama Zona</label>
                                                            <input type="text" name="name" value="{{ $zone->name }}" required class="input">
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-3">
                                                            <div>
                                                                <label class="mb-1 block text-xs font-semibold text-slate-500">Min Jarak (km)</label>
                                                                <input type="number" name="min_distance_km" min="0" value="{{ $zone->min_distance_km }}" required class="input">
                                                            </div>
                                                            <div>
                                                                <label class="mb-1 block text-xs font-semibold text-slate-500">Max Jarak (km)</label>
                                                                <input type="number" name="max_distance_km" min="1" value="{{ $zone->max_distance_km }}" class="input" placeholder="∞">
                                                            </div>
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-3">
                                                            <div>
                                                                <label class="mb-1 block text-xs font-semibold text-slate-500">Tarif / kg</label>
                                                                <input type="number" name="rate_per_kg" min="0" step="0.01" value="{{ $zone->rate_per_kg }}" required class="input">
                                                            </div>
                                                            <div>
                                                                <label class="mb-1 block text-xs font-semibold text-slate-500">Min Charge</label>
                                                                <input type="number" name="min_charge" min="0" step="0.01" value="{{ $zone->min_charge }}" class="input">
                                                            </div>
                                                        </div>
                                                        <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="active" value="1" {{ $zone->active ? 'checked' : '' }} class="h-4 w-4 accent-primary"> Aktif</label>
                                                        <div class="flex items-center gap-2 pt-1">
                                                            <button type="submit" class="btn-primary btn-sm flex-1">Simpan</button>
                                                            <button type="button" class="btn-ghost btn-sm" @click="open = false">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </template>
                                        <form method="POST" action="{{ route('admin.shipping.zones.destroy', $zone) }}" onsubmit="return confirm('Hapus zona ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-ghost btn-sm text-rose-600 dark:text-rose-400">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-10 text-center text-sm text-slate-500">Belum ada zona. Tambahkan minimal Zona 1 (0 km ke atas) agar ongkir selalu bisa dihitung.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- International regions --}}
        <div class="card-flat p-6">
            <h2 class="font-display text-lg font-bold">Region Internasional</h2>
            <p class="mt-1 text-xs text-slate-500">Tarif default per region (Asia, Eropa, dst.) jika negara belum diatur secara spesifik.</p>

            <form method="POST" action="{{ route('admin.shipping.regions.store') }}" class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @csrf
                <input type="text" name="name" placeholder="Contoh: Asia" required class="input">
                <input type="number" name="rate_per_kg" min="0" step="0.01" value="150000" placeholder="Tarif / kg" class="input">
                <input type="number" name="min_charge" min="0" step="0.01" value="50000" placeholder="Min charge" class="input">
                <button class="btn-primary">Tambah Region</button>
            </form>

            <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($regions as $region)
                    <div class="rounded-xl border border-slate-100 p-4 dark:border-white/10">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold">{{ $region->name }}</h3>
                            <form method="POST" action="{{ route('admin.shipping.regions.destroy', $region) }}" onsubmit="return confirm('Hapus region {{ $region->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-rose-600 dark:text-rose-400"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </form>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Rp {{ number_format($region->rate_per_kg, 0, ',', '.') }}/kg · min Rp {{ number_format($region->min_charge, 0, ',', '.') }}</p>
                        @if ($region->countries->isNotEmpty())
                            <p class="mt-2 text-xs text-slate-400">{{ $region->countries->pluck('country')->join(', ') }}</p>
                        @endif
                        <details class="mt-2">
                            <summary class="cursor-pointer text-xs font-bold uppercase tracking-wider text-primary">Edit</summary>
                            <form method="POST" action="{{ route('admin.shipping.regions.update', $region) }}" class="mt-3 space-y-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $region->name }}" required class="input">
                                <input type="number" name="rate_per_kg" min="0" step="0.01" value="{{ $region->rate_per_kg }}" class="input">
                                <input type="number" name="min_charge" min="0" step="0.01" value="{{ $region->min_charge }}" class="input">
                                <button class="btn-primary btn-sm w-full">Simpan</button>
                            </form>
                        </details>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada region internasional.</p>
                @endforelse
            </div>
        </div>

        {{-- International countries --}}
        <div class="card-flat p-6">
            <h2 class="font-display text-lg font-bold">Negara Tujuan Internasional</h2>
            <p class="mt-1 text-xs text-slate-500">Atur tarif per kg dan minimum charge untuk masing-masing negara tujuan.</p>

            <form method="POST" action="{{ route('admin.shipping.countries.store') }}" class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-6">
                @csrf
                <select name="region_id" required class="input">
                    <option value="">Pilih region...</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="country" placeholder="Contoh: Singapura" required class="input lg:col-span-2">
                <input type="number" name="rate_per_kg" min="0" step="0.01" placeholder="Tarif / kg" class="input">
                <input type="number" name="min_charge" min="0" step="0.01" placeholder="Min charge" class="input">
                <button class="btn-primary">Tambah Negara</button>
            </form>

            <div class="table-wrap mt-5">
                <table class="table-base">
                    <thead><tr><th>Negara</th><th>Region</th><th>Tarif / kg</th><th>Min Charge</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
                    <tbody>
                        @forelse ($countries as $country)
                            <tr>
                                <td class="font-semibold">{{ $country->country }}</td>
                                <td>{{ $country->region?->name }}</td>
                                <td>Rp {{ number_format($country->rate_per_kg ?? $country->region?->rate_per_kg ?? 0, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($country->min_charge ?? $country->region?->min_charge ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $country->active ? 'Aktif' : 'Nonaktif' }}</td>
                                <td>
                                    <div x-data="{ open: false }" @keydown.escape.window="open = false" class="flex justify-end gap-1">
                                        <button type="button" class="btn-ghost btn-sm" @click="open = true">Edit</button>
                                        <template x-teleport="body">
                                            <div x-show="open" x-transition.opacity x-cloak class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm" @click="open = false">
                                                <div class="max-h-[90vh] w-full max-w-md overflow-y-auto rounded-2xl border border-slate-100 bg-white p-5 shadow-2xl dark:border-white/10 dark:bg-slate-900" @click.stop>
                                                    <h4 class="mb-4 text-sm font-bold text-slate-700 dark:text-slate-200">Edit Negara</h4>
                                                    <form method="POST" action="{{ route('admin.shipping.countries.update', $country) }}" class="space-y-3.5">
                                                        @csrf
                                                        @method('PUT')
                                                        <div>
                                                            <label class="mb-1 block text-xs font-semibold text-slate-500">Region</label>
                                                            <select name="region_id" required class="input">
                                                                @foreach ($regions as $region)
                                                                    <option value="{{ $region->id }}" {{ $country->region_id === $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="mb-1 block text-xs font-semibold text-slate-500">Nama Negara</label>
                                                            <input type="text" name="country" value="{{ $country->country }}" required class="input">
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-3">
                                                            <div>
                                                                <label class="mb-1 block text-xs font-semibold text-slate-500">Tarif / kg</label>
                                                                <input type="number" name="rate_per_kg" min="0" step="0.01" value="{{ $country->rate_per_kg }}" class="input">
                                                            </div>
                                                            <div>
                                                                <label class="mb-1 block text-xs font-semibold text-slate-500">Min Charge</label>
                                                                <input type="number" name="min_charge" min="0" step="0.01" value="{{ $country->min_charge }}" class="input">
                                                            </div>
                                                        </div>
                                                        <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="active" value="1" {{ $country->active ? 'checked' : '' }} class="h-4 w-4 accent-primary"> Aktif</label>
                                                        <div class="flex items-center gap-2 pt-1">
                                                            <button type="submit" class="btn-primary btn-sm flex-1">Simpan</button>
                                                            <button type="button" class="btn-ghost btn-sm" @click="open = false">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </template>
                                        <form method="POST" action="{{ route('admin.shipping.countries.destroy', $country) }}" onsubmit="return confirm('Hapus negara ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-ghost btn-sm text-rose-600 dark:text-rose-400">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-10 text-center text-sm text-slate-500">Belum ada negara tujuan internasional.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Couriers --}}
        <div class="card-flat p-6">
            <h2 class="font-display text-lg font-bold">Layanan Pengiriman</h2>
            <form method="POST" action="{{ route('admin.shipping.couriers.store') }}" class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @csrf
                <input type="text" name="name" placeholder="Contoh: JNE" required class="input">
                <select name="type" required class="input">
                    <option value="domestic">Domestik</option>
                    <option value="international">Internasional</option>
                </select>
                <button class="btn-primary">Tambah Layanan</button>
                <label class="flex items-center gap-2 text-sm font-semibold text-slate-600 lg:col-span-3">
                    <input type="checkbox" name="active" value="1" checked class="h-4 w-4 accent-primary"> Aktif
                </label>
            </form>
            <div class="mt-4 flex flex-wrap gap-2">
                @forelse ($couriers as $courier)
                    <span class="badge-primary inline-flex items-center gap-2">
                        {{ $courier->name }}
                        <span class="text-[10px] uppercase opacity-70">{{ $courier->type }}</span>
                        @if (! $courier->active)<span class="text-[10px] opacity-60">(nonaktif)</span>@endif
                        <form method="POST" action="{{ route('admin.shipping.couriers.destroy', $courier) }}" class="inline" onsubmit="return confirm('Hapus layanan {{ $courier->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-current hover:opacity-60">×</button>
                        </form>
                    </span>
                @empty
                    <p class="text-sm text-slate-500">Belum ada layanan pengiriman.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection