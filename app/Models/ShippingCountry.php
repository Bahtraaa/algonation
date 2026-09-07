<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingCountry extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'country',
        'rate_per_kg',
        'min_charge',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'rate_per_kg' => 'decimal:2',
            'min_charge'  => 'decimal:2',
            'active'      => 'boolean',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(InternationalRegion::class, 'region_id');
    }
}
