<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:20',
                // Format Indonesia: 08xx / 62xx / +62xx, boleh spasi/strip.
                'regex:/^(\+62|62|0)[0-9\s\-]{8,16}$/',
            ],
            'country' => ['required', 'string', 'max:120'],
            'province' => ['required', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'district' => ['required', 'string', 'max:120'],
            'postal_code' => ['required', 'string', 'max:10', 'regex:/^[0-9A-Za-z\s\-]{3,10}$/'],
            'address' => ['required', 'string', 'max:1000'],
            'note' => ['nullable', 'string', 'max:500'],
            'is_default' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'label.required' => 'Label alamat wajib diisi (contoh: Rumah, Kantor, Sekolah).',
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'phone.required' => 'Nomor WhatsApp/Telepon wajib diisi.',
            'phone.regex' => 'Format nomor telepon tidak valid (contoh: 081234567890).',
            'country.required' => 'Negara wajib diisi.',
            'province.required' => 'Provinsi wajib diisi.',
            'city.required' => 'Kota/Kabupaten wajib diisi.',
            'district.required' => 'Kecamatan wajib diisi.',
            'postal_code.required' => 'Kode pos wajib diisi.',
            'postal_code.regex' => 'Format kode pos tidak valid.',
            'address.required' => 'Alamat lengkap wajib diisi.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Checkbox "is_default" hanya terkirim saat dicentang.
        $this->merge([
            'is_default' => $this->boolean('is_default'),
            'country' => $this->input('country', 'Indonesia'),
        ]);
    }
}
