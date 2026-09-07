@extends('layouts.admin')

@section('title', 'Flash Sale')
@section('page-title', 'Flash Sale')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="card-flat p-6">
            <h2 class="font-display text-lg font-bold">Tambah Flash Sale</h2>
            <form method="POST" action="{{ route('admin.flash-sales.store') }}" class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @csrf
                <select name="product_id" required class="input lg:col-span-2"><option value="">Pilih produk</option>@foreach ($products as $product)<option value="{{ $product->id }}">{{ $product->name }} (Rp {{ number_format($product->display_price, 0, ',', '.') }})</option>@endforeach</select>
                <input name="normal_price" type="number" min="0" step="0.01" placeholder="Harga normal" required class="input">
                <input name="sale_price" type="number" min="0" step="0.01" placeholder="Harga flash sale" required class="input">
                <input name="stock" type="number" min="0" placeholder="Stok flash sale" required class="input">
                <label class="text-xs font-semibold text-slate-500">Mulai WIB<input name="start_at" type="datetime-local" required class="input mt-1"></label>
                <label class="text-xs font-semibold text-slate-500">Berakhir WIB<input name="end_at" type="datetime-local" required class="input mt-1"></label>
                <button class="btn-primary self-end">Simpan Flash Sale</button>
            </form>
            @if ($errors->any())<div class="mt-4 text-sm text-rose-600">{{ $errors->first() }}</div>@endif
        </div>

        <div class="table-wrap">
            <table class="table-base"><thead><tr><th>Produk</th><th>Harga</th><th>Diskon</th><th>Stok</th><th>Periode WIB</th><th>Status</th><th class="text-right">Aksi</th></tr></thead><tbody>
                @forelse ($flashSales as $sale)
                    <tr><td><div class="flex items-center gap-3"><img src="{{ $sale->product->image_url }}" class="h-11 w-11 rounded-xl object-cover" alt=""><span class="font-semibold">{{ $sale->product->name }}</span></div></td>
                    <td><del class="text-xs text-slate-400">Rp {{ number_format($sale->normal_price, 0, ',', '.') }}</del><br><b>Rp {{ number_format($sale->sale_price, 0, ',', '.') }}</b></td>
                    <td>{{ $sale->discount_percentage }}%</td><td>{{ $sale->stock }}</td>
                    <td class="text-xs">{{ $sale->start_at->format('d/m/Y H:i') }}<br>{{ $sale->end_at->format('d/m/Y H:i') }}</td>
                    <td><x-status-badge :variant="$sale->computed_status === 'active' ? 'badge-success' : ($sale->computed_status === 'expired' ? 'badge-danger' : 'badge-warning')">{{ ucfirst($sale->computed_status) }}</x-status-badge></td>
                    <td><div class="flex justify-end gap-1"><form method="POST" action="{{ route('admin.flash-sales.status', $sale) }}">@csrf @method('PATCH')<button class="btn-outline btn-sm">{{ $sale->status === 'inactive' ? 'Aktifkan' : 'Nonaktifkan' }}</button></form><form method="POST" action="{{ route('admin.flash-sales.destroy', $sale) }}" onsubmit="return confirm('Hapus flash sale ini?')">@csrf @method('DELETE')<button class="btn-danger btn-sm">Hapus</button></form></div></td></tr>
                    <tr><td colspan="7"><details><summary class="cursor-pointer text-xs font-bold uppercase tracking-wider text-primary">Edit flash sale</summary><form method="POST" action="{{ route('admin.flash-sales.update', $sale) }}" class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">@csrf @method('PUT')<select name="product_id" required class="input lg:col-span-2">@foreach ($products as $product)<option value="{{ $product->id }}" {{ $sale->product_id === $product->id ? 'selected' : '' }}>{{ $product->name }}</option>@endforeach</select><input name="normal_price" type="number" min="0" step="0.01" value="{{ $sale->normal_price }}" required class="input"><input name="sale_price" type="number" min="0" step="0.01" value="{{ $sale->sale_price }}" required class="input"><input name="stock" type="number" min="0" value="{{ $sale->stock }}" required class="input"><input name="start_at" type="datetime-local" value="{{ $sale->start_at->format('Y-m-d\\TH:i') }}" required class="input"><input name="end_at" type="datetime-local" value="{{ $sale->end_at->format('Y-m-d\\TH:i') }}" required class="input"><button class="btn-primary">Simpan Perubahan</button></form></details></td></tr>
                @empty <tr><td colspan="7" class="py-12 text-center">Belum ada flash sale.</td></tr>@endforelse
            </tbody></table>
        </div>
    </div>
@endsection
