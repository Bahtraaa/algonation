<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_distance_km',
        'max_distance_km',
        'rate_per_kg',
        'min_charge',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'min_distance_km' => 'decimal:2',
            'max_distance_km' => 'decimal:2',
            'rate_per_kg' => 'decimal:2',
            'min_charge' => 'decimal:2',
            'active' => 'boolean',
        ];
    }
}
