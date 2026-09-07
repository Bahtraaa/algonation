<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternationalRegion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate_per_kg',
        'min_charge',
    ];

    protected function casts(): array
    {
        return [
            'rate_per_kg' => 'decimal:2',
            'min_charge'  => 'decimal:2',
        ];
    }

    public function countries()
    {
        return $this->hasMany(ShippingCountry::class, 'region_id');
    }
}
