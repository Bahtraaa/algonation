<?php

namespace Database\Seeders;

use App\Models\InternationalRegion;
use App\Models\ShippingCountry;
use App\Models\ShippingCourier;
use App\Models\ShippingSetting;
use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    /**
     * Seed the store origin, domestic zones, international regions/countries
     * and couriers so checkout can always calculate a shipping cost.
     */
    public function run(): void
    {
        // ---- Store origin (Jakarta) ----
        ShippingSetting::query()->whereNotIn('id', ShippingSetting::query()->pluck('id'))->delete();

        ShippingSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'origin_country'    => 'Indonesia',
                'origin_city'       => 'Jakarta',
                'origin_latitude'   => -6.200000,
                'origin_longitude'  => 106.816666,
                'routing_provider'  => 'none',
                'enable_routing'    => false,
            ]
        );

        // ---- Domestic zones (distance in km) ----
        $zones = [
            ['name' => 'Zona 1',            'min_distance_km' => 0,     'max_distance_km' => 10,    'rate_per_kg' => 10000,  'min_charge' => 10000,  'active' => true],
            ['name' => 'Zona 2',            'min_distance_km' => 11,    'max_distance_km' => 20,    'rate_per_kg' => 12000,  'min_charge' => 12000,  'active' => true],
            ['name' => 'Zona 3',            'min_distance_km' => 21,    'max_distance_km' => 30,   'rate_per_kg' => 15000,  'min_charge' => 15000,  'active' => true],
            ['name' => 'Zona 4',            'min_distance_km' => 31,   'max_distance_km' => 100,   'rate_per_kg' => 18000,  'min_charge' => 18000,  'active' => true],
            ['name' => 'Zona 5',            'min_distance_km' => 101,   'max_distance_km' => 200,  'rate_per_kg' => 22000,  'min_charge' => 22000,  'active' => true],
            ['name' => 'Zona 6',            'min_distance_km' => 201,  'max_distance_km' => 500,  'rate_per_kg' => 25000,  'min_charge' => 25000,  'active' => true],
            ['name' => 'Zona 7',            'min_distance_km' => 501,  'max_distance_km' => 700, 'rate_per_kg' => 30000,  'min_charge' => 30000,  'active' => true],
            ['name' => 'Zona 8',            'min_distance_km' => 701,  'max_distance_km' => 1060, 'rate_per_kg' => 35000,  'min_charge' => 35000,  'active' => true],
            ['name' => 'Zona 9',            'min_distance_km' => 1061,  'max_distance_km' => null, 'rate_per_kg' => 100000,  'min_charge' => 60000,  'active' => true],
        ];

        foreach ($zones as $zone) {
            ShippingZone::query()->updateOrCreate(
                ['name' => $zone['name']],
                $zone
            );
        }

        // ---- International regions ----
        $regions = [
            ['name' => 'Asia',                 'rate_per_kg' => 150000, 'min_charge' => 100000],
            ['name' => 'Asia Tenggara',        'rate_per_kg' => 90000,  'min_charge' => 75000],
            ['name' => 'Eropa',                'rate_per_kg' => 250000, 'min_charge' => 200000],
            ['name' => 'Amerika Utara',        'rate_per_kg' => 300000, 'min_charge' => 250000],
        ];

        foreach ($regions as $region) {
            InternationalRegion::query()->updateOrCreate(
                ['name' => $region['name']],
                $region
            );
        }

        // ---- International destination countries ----
        $asiaTenggara  = InternationalRegion::query()->where('name', 'Asia Tenggara')->first();
        $asia          = InternationalRegion::query()->where('name', 'Asia')->first();
        $eropa         = InternationalRegion::query()->where('name', 'Eropa')->first();
        $amerikaUtara  = InternationalRegion::query()->where('name', 'Amerika Utara')->first();

        $countries = [
            ['country' => 'Singapura', 'region_id' => $asiaTenggara?->id, 'rate_per_kg' => 90000,  'min_charge' => 75000, 'active' => true],
            ['country' => 'Malaysia',  'region_id' => $asiaTenggara?->id, 'rate_per_kg' => 95000,  'min_charge' => 75000, 'active' => true],
            ['country' => 'Thailand',  'region_id' => $asiaTenggara?->id, 'rate_per_kg' => 110000, 'min_charge' => 90000, 'active' => true],
            ['country' => 'Jepang',    'region_id' => $asia?->id,         'rate_per_kg' => 180000, 'min_charge' => 150000, 'active' => true],
            ['country' => 'Korea Selatan', 'region_id' => $asia?->id,     'rate_per_kg' => 180000, 'min_charge' => 150000, 'active' => true],
            ['country' => 'Belanda',   'region_id' => $eropa?->id,        'rate_per_kg' => 250000, 'min_charge' => 200000, 'active' => true],
            ['country' => 'Inggris',   'region_id' => $eropa?->id,        'rate_per_kg' => 280000, 'min_charge' => 220000, 'active' => true],
            ['country' => 'Amerika Serikat', 'region_id' => $amerikaUtara?->id, 'rate_per_kg' => 300000, 'min_charge' => 250000, 'active' => true],
        ];

        foreach ($countries as $country) {
            ShippingCountry::query()->updateOrCreate(
                ['country' => $country['country']],
                $country
            );
        }

        // ---- Couriers ----
        $couriers = [
            ['name' => 'JNE',                 'type' => 'domestic',      'active' => true],
            ['name' => 'J&T Express',         'type' => 'domestic',      'active' => true],
            ['name' => 'DHL Express',         'type' => 'international', 'active' => true],
            ['name' => 'POS Indonesia',       'type' => 'domestic',      'active' => true],
        ];

        foreach ($couriers as $courier) {
            ShippingCourier::query()->updateOrCreate(
                ['name' => $courier['name']],
                $courier
            );
        }
    }
}
