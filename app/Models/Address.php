<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'country',
        'province',
        'city',
        'district',
        'postal_code',
        'address',
        'note',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    /**
     * Pemilik alamat.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order yang memakai alamat ini saat checkout.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Tandai alamat ini sebagai default milik user.
     * Satu user hanya boleh punya satu default — panggil di dalam DB::transaction.
     */
    public function markAsDefault(): void
    {
        static::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        if (! $this->is_default) {
            $this->update(['is_default' => true]);
        }
    }

    /**
     * Baris alamat lengkap untuk ditampilkan di checkout / invoice.
     */
    public function getFullAddressAttribute(): string
    {
        return trim(
            $this->address."\n".
            $this->district.', '.$this->city."\n".
            $this->province.', '.$this->postal_code."\n".
            $this->country.
            ($this->note ? "\nCatatan: ".$this->note : '')
        );
    }

    /**
     * Scope: alamat default milik user.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
