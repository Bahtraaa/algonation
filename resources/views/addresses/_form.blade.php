{{-- Form alamat pengiriman (dipakai create & edit) --}}
@php($address = $address ?? null)
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="label">Label Alamat</label>
        <input type="text" name="label" list="label-suggestions" value="{{ old('label', $address->label ?? 'Rumah') }}" required class="input" placeholder="Rumah / Kantor / Sekolah">
        <datalist id="label-suggestions">
            <option value="Rumah"></option>
            <option value="Kantor"></option>
            <option value="Sekolah"></option>
            <option value="Apartemen"></option>
            <option value="Lainnya"></option>
        </datalist>
        @error('label')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="flex items-end pb-1">
        <label class="flex cursor-pointer items-center gap-2 text-sm font-medium">
            <input type="checkbox" name="is_default" value="1" {{ old('is_default', $address->is_default ?? false) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 accent-[#1b1b18]">
            Jadikan alamat utama
        </label>
    </div>
    <div>
        <label class="label">Nama Penerima</label>
        <input type="text" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name ?? auth()->user()->name) }}" required class="input" placeholder="Nama penerima">
        @error('recipient_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="label">Nomor WhatsApp / Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $address->phone ?? '') }}" required class="input" placeholder="08xxxxxxxxxx">
        @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="label">Negara</label>
        <input type="text" name="country" value="{{ old('country', $address->country ?? 'Indonesia') }}" required class="input" placeholder="Indonesia">
        @error('country')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="label">Provinsi</label>
        <input type="text" name="province" value="{{ old('province', $address->province ?? '') }}" required class="input" placeholder="Jawa Barat">
        @error('province')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="label">Kota / Kabupaten</label>
        <input type="text" name="city" value="{{ old('city', $address->city ?? '') }}" required class="input" placeholder="Contoh: Bandung">
        @error('city')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="label">Kecamatan</label>
        <input type="text" name="district" value="{{ old('district', $address->district ?? '') }}" required class="input" placeholder="Contoh: Coblong">
        @error('district')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <label class="label">Kode Pos</label>
        <input type="text" name="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}" required class="input" placeholder="40132" maxlength="10">
        @error('postal_code')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <label class="label">Alamat Lengkap</label>
        <textarea name="address" rows="3" required class="input" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan">{{ old('address', $address->address ?? '') }}</textarea>
        @error('address')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <label class="label">Catatan Alamat <span class="text-slate-400">(opsional)</span></label>
        <textarea name="note" rows="2" class="input" placeholder="Patokan, jam pengiriman, titip ke siapa, dll.">{{ old('note', $address->note ?? '') }}</textarea>
        @error('note')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
</div>

@if (! empty($returnTo))
    <input type="hidden" name="return_to" value="{{ $returnTo }}">
@endif
