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
            ['name' => 'Zona 9',            'min_distance_km' => 1061,  'max_distance_km' => null, 'rate_per_kg' => 150000,  'min_charge' => 60000,  'active' => true],
        ];

        foreach ($zones as $zone) {
            ShippingZone::query()->updateOrCreate(
                ['name' => $zone['name']],
                $zone
            );
        }

        // ---- International regions ----
        // rate_per_kg region = tarif fallback "negara lainnya" di region tsb.
        $regions = [
            ['name' => 'Asia Tenggara',             'rate_per_kg' => 450000,  'min_charge' => 450000],
            ['name' => 'Asia',                      'rate_per_kg' => 900000,  'min_charge' => 900000],
            ['name' => 'Australia & Selandia Baru', 'rate_per_kg' => 750000,  'min_charge' => 750000],
            ['name' => 'Eropa',                     'rate_per_kg' => 1300000, 'min_charge' => 1300000],
            ['name' => 'Amerika Utara',             'rate_per_kg' => 1300000, 'min_charge' => 1300000],
            ['name' => 'Amerika Selatan',           'rate_per_kg' => 1400000, 'min_charge' => 1400000],
            ['name' => 'Timur Tengah',              'rate_per_kg' => 1150000, 'min_charge' => 1150000],
            ['name' => 'Afrika',                    'rate_per_kg' => 1350000, 'min_charge' => 1350000],
        ];

        foreach ($regions as $region) {
            InternationalRegion::query()->updateOrCreate(
                ['name' => $region['name']],
                $region
            );
        }

        // ---- International destination countries ----
        // Tarif = harga ongkir yang dibayar customer untuk setiap 1 KG.
        // min_charge disamakan dengan rate (minimum tagihan 1 KG).
        $asiaTenggara  = InternationalRegion::query()->where('name', 'Asia Tenggara')->first();
        $asia          = InternationalRegion::query()->where('name', 'Asia')->first();
        $oseania       = InternationalRegion::query()->where('name', 'Australia & Selandia Baru')->first();
        $eropa         = InternationalRegion::query()->where('name', 'Eropa')->first();
        $amerikaUtara  = InternationalRegion::query()->where('name', 'Amerika Utara')->first();
        $amerikaSelatan = InternationalRegion::query()->where('name', 'Amerika Selatan')->first();
        $timurTengah   = InternationalRegion::query()->where('name', 'Timur Tengah')->first();
        $afrika        = InternationalRegion::query()->where('name', 'Afrika')->first();

        $countries = [
            // Asia Tenggara
            ['country' => 'Malaysia',  'region_id' => $asiaTenggara?->id, 'rate_per_kg' => 470000,  'min_charge' => 470000,  'active' => true],
            ['country' => 'Singapura', 'region_id' => $asiaTenggara?->id, 'rate_per_kg' => 450000,  'min_charge' => 450000,  'active' => true],
            ['country' => 'Brunei',    'region_id' => $asiaTenggara?->id, 'rate_per_kg' => 500000,  'min_charge' => 500000,  'active' => true],
            ['country' => 'Thailand',  'region_id' => $asiaTenggara?->id, 'rate_per_kg' => 600000,  'min_charge' => 600000,  'active' => true],
            ['country' => 'Filipina',  'region_id' => $asiaTenggara?->id, 'rate_per_kg' => 630000,  'min_charge' => 630000,  'active' => true],
            ['country' => 'Vietnam',   'region_id' => $asiaTenggara?->id, 'rate_per_kg' => 640000,  'min_charge' => 640000,  'active' => true],
            // Asia di luar Asia Tenggara
            ['country' => 'Jepang',        'region_id' => $asia?->id, 'rate_per_kg' => 900000, 'min_charge' => 900000, 'active' => true],
            ['country' => 'Korea Selatan', 'region_id' => $asia?->id, 'rate_per_kg' => 900000, 'min_charge' => 900000, 'active' => true],
            ['country' => 'China',         'region_id' => $asia?->id, 'rate_per_kg' => 900000, 'min_charge' => 900000, 'active' => true],
            ['country' => 'Taiwan',        'region_id' => $asia?->id, 'rate_per_kg' => 900000, 'min_charge' => 900000, 'active' => true],
            ['country' => 'Hong Kong',     'region_id' => $asia?->id, 'rate_per_kg' => 900000, 'min_charge' => 900000, 'active' => true],
            // Australia & Selandia Baru
            ['country' => 'Australia',     'region_id' => $oseania?->id, 'rate_per_kg' => 750000, 'min_charge' => 750000, 'active' => true],
            ['country' => 'Selandia Baru', 'region_id' => $oseania?->id, 'rate_per_kg' => 750000, 'min_charge' => 750000, 'active' => true],
            // Eropa (negara Eropa lainnya ikut tarif fallback region: 1.300.000)
            ['country' => 'Inggris', 'region_id' => $eropa?->id, 'rate_per_kg' => 1200000, 'min_charge' => 1200000, 'active' => true],
            ['country' => 'Jerman',  'region_id' => $eropa?->id, 'rate_per_kg' => 1200000, 'min_charge' => 1200000, 'active' => true],
            ['country' => 'Prancis', 'region_id' => $eropa?->id, 'rate_per_kg' => 1200000, 'min_charge' => 1200000, 'active' => true],
            ['country' => 'Belanda', 'region_id' => $eropa?->id, 'rate_per_kg' => 1200000, 'min_charge' => 1200000, 'active' => true],
            ['country' => 'Italia',  'region_id' => $eropa?->id, 'rate_per_kg' => 1250000, 'min_charge' => 1250000, 'active' => true],
            ['country' => 'Spanyol', 'region_id' => $eropa?->id, 'rate_per_kg' => 1250000, 'min_charge' => 1250000, 'active' => true],
            // Amerika Utara
            ['country' => 'Amerika Serikat', 'region_id' => $amerikaUtara?->id, 'rate_per_kg' => 1300000, 'min_charge' => 1300000, 'active' => true],
            ['country' => 'Kanada',          'region_id' => $amerikaUtara?->id, 'rate_per_kg' => 1350000, 'min_charge' => 1350000, 'active' => true],
            // Amerika Selatan
            ['country' => 'Brasil',    'region_id' => $amerikaSelatan?->id, 'rate_per_kg' => 1450000, 'min_charge' => 1450000, 'active' => true],
            ['country' => 'Argentina', 'region_id' => $amerikaSelatan?->id, 'rate_per_kg' => 1500000, 'min_charge' => 1500000, 'active' => true],
            ['country' => 'Chile',     'region_id' => $amerikaSelatan?->id, 'rate_per_kg' => 1400000, 'min_charge' => 1400000, 'active' => true],
            // Timur Tengah
            ['country' => 'Uni Emirat Arab', 'region_id' => $timurTengah?->id, 'rate_per_kg' => 1150000, 'min_charge' => 1150000, 'active' => true],
            ['country' => 'Arab Saudi',      'region_id' => $timurTengah?->id, 'rate_per_kg' => 1150000, 'min_charge' => 1150000, 'active' => true],
            ['country' => 'Qatar',           'region_id' => $timurTengah?->id, 'rate_per_kg' => 1200000, 'min_charge' => 1200000, 'active' => true],
            ['country' => 'Kuwait',          'region_id' => $timurTengah?->id, 'rate_per_kg' => 1200000, 'min_charge' => 1200000, 'active' => true],
            // Afrika
            ['country' => 'Afrika Selatan', 'region_id' => $afrika?->id, 'rate_per_kg' => 1350000, 'min_charge' => 1350000, 'active' => true],
            ['country' => 'Mesir',          'region_id' => $afrika?->id, 'rate_per_kg' => 1350000, 'min_charge' => 1350000, 'active' => true],
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
