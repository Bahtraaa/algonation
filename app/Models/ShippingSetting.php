<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_country',
        'origin_city',
        'origin_latitude',
        'origin_longitude',
        'routing_provider',
        'enable_routing',
    ];

    protected function casts(): array
    {
        return [
            'origin_latitude'  => 'decimal:7',
            'origin_longitude' => 'decimal:7',
            'enable_routing'   => 'boolean',
        ];
    }
}
