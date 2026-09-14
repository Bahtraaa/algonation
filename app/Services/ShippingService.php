<?php

namespace App\Services;

use App\Models\ShippingCountry;
use App\Models\ShippingCourier;
use App\Models\ShippingSetting;
use App\Models\ShippingZone;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Shipping domain logic.
 *
 * This is the SINGLE source of truth for every shipping rule in the app:
 * distance calculation, volumetric weight, billable weight, zone lookup,
 * rate resolution and the final shipping cost. Controllers and blade views
 * must never inline these formulas.
 *
 * Two responsibilities live here:
 *  1. Checkout/server-side shipping cost calculation (domestic + international).
 *  2. Admin shipping-status lifecycle transitions (legacy, see validateTransition).
 */
class ShippingService
{
    /**
     * Volumetric weight divisor for DOMESTIC shipments (JNE/J&T standard).
     */
    public const DOMESTIC_VOLUMETRIC_DIVISOR = 6000;

    /**
     * Volumetric weight divisor for INTERNATIONAL shipments (IATA standard).
     */
    public const INTERNATIONAL_VOLUMETRIC_DIVISOR = 5000;

    /**
     * Earth radius in kilometres (Haversine).
     */
    public const EARTH_RADIUS_KM = 6371;

    /**
     * Localized country names stored in addresses => English names Nominatim understands.
     */
    protected const COUNTRY_ALIASES = [
        'Amerika Serikat' => 'United States',
        'Singapura' => 'Singapore',
        'Jepang' => 'Japan',
        'Tiongkok' => 'China',
        'China' => 'China',
        'Taiwan' => 'Taiwan',
        'Hong Kong' => 'Hong Kong',
        'Hongkong' => 'Hong Kong',
        'Korea Selatan' => 'South Korea',
        'Korea Utara' => 'North Korea',
        'Belanda' => 'Netherlands',
        'Inggris' => 'United Kingdom',
        'Jerman' => 'Germany',
        'Prancis' => 'France',
        'Spanyol' => 'Spain',
        'Italia' => 'Italy',
        'Filipina' => 'Philippines',
        'Thailand' => 'Thailand',
        'Vietnam' => 'Vietnam',
        'Brunei' => 'Brunei',
        'Brunei Darussalam' => 'Brunei',
        'Arab Saudi' => 'Saudi Arabia',
        'Uni Emirat Arab' => 'United Arab Emirates',
        'Qatar' => 'Qatar',
        'Kuwait' => 'Kuwait',
        'Selandia Baru' => 'New Zealand',
        'Afrika Selatan' => 'South Africa',
        'Mesir' => 'Egypt',
        'Kanada' => 'Canada',
        'Brasil' => 'Brazil',
        'Argentina' => 'Argentina',
        'Chile' => 'Chile',
        'Australia' => 'Australia',
        'Malaysia' => 'Malaysia',
        'India' => 'India',
    ];

    /**
     * Offline safety net: approximate country centres (capital/centroid) so an
     * international distance is never silently reported as 0 km when the
     * geocoding API is unreachable or returns nothing. Keys are lowercase and
     * cover both Indonesian and English names.
     *
     * @var array<string, array{latitude: float, longitude: float}>
     */
    protected const COUNTRY_COORDINATES = [
        'indonesia' => ['latitude' => -2.5, 'longitude' => 118.0],
        'amerika serikat' => ['latitude' => 39.8, 'longitude' => -98.5],
        'united states' => ['latitude' => 39.8, 'longitude' => -98.5],
        'usa' => ['latitude' => 39.8, 'longitude' => -98.5],
        'singapura' => ['latitude' => 1.35, 'longitude' => 103.8],
        'singapore' => ['latitude' => 1.35, 'longitude' => 103.8],
        'malaysia' => ['latitude' => 4.2, 'longitude' => 102.2],
        'jepang' => ['latitude' => 36.2, 'longitude' => 138.2],
        'japan' => ['latitude' => 36.2, 'longitude' => 138.2],
        'tiongkok' => ['latitude' => 35.8, 'longitude' => 104.2],
        'china' => ['latitude' => 35.8, 'longitude' => 104.2],
        'korea selatan' => ['latitude' => 36.3, 'longitude' => 127.9],
        'south korea' => ['latitude' => 36.3, 'longitude' => 127.9],
        'india' => ['latitude' => 21.0, 'longitude' => 78.0],
        'australia' => ['latitude' => -25.3, 'longitude' => 133.8],
        'inggris' => ['latitude' => 54.0, 'longitude' => -2.0],
        'united kingdom' => ['latitude' => 54.0, 'longitude' => -2.0],
        'uk' => ['latitude' => 54.0, 'longitude' => -2.0],
        'belanda' => ['latitude' => 52.2, 'longitude' => 5.3],
        'netherlands' => ['latitude' => 52.2, 'longitude' => 5.3],
        'jerman' => ['latitude' => 51.2, 'longitude' => 10.4],
        'germany' => ['latitude' => 51.2, 'longitude' => 10.4],
        'prancis' => ['latitude' => 46.6, 'longitude' => 2.4],
        'france' => ['latitude' => 46.6, 'longitude' => 2.4],
        'thailand' => ['latitude' => 15.9, 'longitude' => 100.9],
        'filipina' => ['latitude' => 13.0, 'longitude' => 122.0],
        'philippines' => ['latitude' => 13.0, 'longitude' => 122.0],
        'vietnam' => ['latitude' => 14.0, 'longitude' => 108.0],
        'arab saudi' => ['latitude' => 24.0, 'longitude' => 45.0],
        'saudi arabia' => ['latitude' => 24.0, 'longitude' => 45.0],
        'uni emirat arab' => ['latitude' => 24.0, 'longitude' => 54.0],
        'united arab emirates' => ['latitude' => 24.0, 'longitude' => 54.0],
        'kanada' => ['latitude' => 56.1, 'longitude' => -106.3],
        'canada' => ['latitude' => 56.1, 'longitude' => -106.3],
        'selandia baru' => ['latitude' => -40.9, 'longitude' => 174.9],
        'new zealand' => ['latitude' => -40.9, 'longitude' => 174.9],
        'brunei' => ['latitude' => 4.9, 'longitude' => 114.9],
        'brunei darussalam' => ['latitude' => 4.9, 'longitude' => 114.9],
        'china' => ['latitude' => 35.8, 'longitude' => 104.2],
        'tiongkok' => ['latitude' => 35.8, 'longitude' => 104.2],
        'taiwan' => ['latitude' => 23.7, 'longitude' => 121.0],
        'hong kong' => ['latitude' => 22.3, 'longitude' => 114.2],
        'hongkong' => ['latitude' => 22.3, 'longitude' => 114.2],
        'italia' => ['latitude' => 41.9, 'longitude' => 12.5],
        'italy' => ['latitude' => 41.9, 'longitude' => 12.5],
        'spanyol' => ['latitude' => 40.4, 'longitude' => -3.7],
        'spain' => ['latitude' => 40.4, 'longitude' => -3.7],
        'brasil' => ['latitude' => -14.2, 'longitude' => -51.9],
        'brazil' => ['latitude' => -14.2, 'longitude' => -51.9],
        'argentina' => ['latitude' => -38.4, 'longitude' => -63.6],
        'chile' => ['latitude' => -35.7, 'longitude' => -71.5],
        'qatar' => ['latitude' => 25.3, 'longitude' => 51.2],
        'kuwait' => ['latitude' => 29.3, 'longitude' => 47.5],
        'mesir' => ['latitude' => 26.8, 'longitude' => 30.8],
        'egypt' => ['latitude' => 26.8, 'longitude' => 30.8],
        'afrika selatan' => ['latitude' => -30.6, 'longitude' => 22.9],
        'south africa' => ['latitude' => -30.6, 'longitude' => 22.9],
    ];

    // ------------------------------------------------------------------
    // Public façade
    // ------------------------------------------------------------------

    /**
     * Full shipping calculation for a checkout destination.
     *
     * @param  array<string, mixed>  $items  Cart items, each needing:
     *                                       - quantity
     *                                       - weight_kg (or weight)
     *                                       - dimensions (list of cm)
     * @param  array<string, mixed>  $destination  ['country', 'city', 'state', 'postal_code', 'latitude', 'longitude']
     * @return array<string, mixed>
     */
    public function calculateShipping(array $items, array $destination): array
    {
        $origin = $this->defaultOrigin();

        $shippingType = $this->determineShippingType($origin['country'], $destination['country']);
        $actualWeight = $this->actualWeight($items);
        $volumetric = $this->volumetricWeight($items, $shippingType);
        $billable = $this->billableWeight($actualWeight, $volumetric);

        ['distance' => $distance, 'estimated' => $distanceEstimated] = $this->shippingDistanceWithFlag($origin, $destination);

        $zone = null;
        $rate = 0.0;
        $minCharge = 0.0;
        $region = null;
        $country = null;

        if ($shippingType === 'domestic') {
            $zone = $this->determineShippingZone($distance);
            $rate = $zone ? (float) $zone->rate_per_kg : 0.0;
            $minCharge = $zone ? (float) $zone->min_charge : 0.0;
        } else {
            $countryCfg = $this->lookupCountry($destination['country']);
            $country = $countryCfg;
            $region = $countryCfg?->region;
            $rate = $countryCfg ? (float) ($countryCfg->rate_per_kg ?? $countryCfg->region?->rate_per_kg ?? 0) : 0.0;
            $minCharge = $countryCfg ? (float) ($countryCfg->min_charge ?? $countryCfg->region?->min_charge ?? 0) : 0.0;
        }

        $cost = $this->shippingCost($billable, $rate, $minCharge);
        $courier = $this->defaultCourier($shippingType);

        return [
            'shipping_type' => $shippingType,
            'origin_country' => $origin['country'],
            'destination_country' => $destination['country'],
            'destination_city' => $destination['city'] ?? null,
            'destination_state' => $destination['state'] ?? null,
            'destination_postal_code' => $destination['postal_code'] ?? null,
            'distance' => $distance,
            'distance_estimated' => $distanceEstimated,
            'actual_weight' => $actualWeight,
            'volumetric_weight' => $volumetric,
            'billable_weight' => $billable,
            'shipping_zone' => $zone?->name,
            'region' => $region?->name,
            'rate' => $rate,
            'min_charge' => $minCharge,
            'shipping_cost' => $cost,
            'shipping_courier' => $courier,
        ];
    }

    /**
     * Shipping cost with a minimum charge floor.
     */
    public function shippingCost(float $billableWeight, float $ratePerKg, float $minCharge = 0): float
    {
        $cost = $billableWeight * $ratePerKg;

        return round(max($cost, $minCharge), 2);
    }

    // ------------------------------------------------------------------
    // Type / weight
    // ------------------------------------------------------------------

    public function determineShippingType(string $originCountry, string $destinationCountry): string
    {
        return strcasecmp(trim($originCountry), trim($destinationCountry)) === 0
            ? 'domestic'
            : 'international';
    }

    public function actualWeight(array $items): float
    {
        $total = 0.0;
        foreach ($items as $item) {
            $qty = (float) ($item['quantity'] ?? 1);
            $perUnit = (float) ($item['weight_kg'] ?? $item['weight'] ?? 0);
            $total += $perUnit * $qty;
        }

        return round($total, 2);
    }

    public function volumetricWeight(array $items, string $shippingType): float
    {
        $divisor = $shippingType === 'international'
            ? self::INTERNATIONAL_VOLUMETRIC_DIVISOR
            : self::DOMESTIC_VOLUMETRIC_DIVISOR;

        $total = 0.0;
        foreach ($items as $item) {
            $qty = (float) ($item['quantity'] ?? 1);
            $dims = $item['dimensions'] ?? [];
            $length = (float) ($dims['length'] ?? 0);
            $width = (float) ($dims['width'] ?? 0);
            $height = (float) ($dims['height'] ?? 0);

            if ($length <= 0 || $width <= 0 || $height <= 0) {
                continue;
            }

            $total += ($length * $width * $height / $divisor) * $qty;
        }

        return round($total, 2);
    }

    public function billableWeight(float $actualWeight, float $volumetricWeight): float
    {
        return round(max($actualWeight, $volumetricWeight), 2);
    }

    // ------------------------------------------------------------------
    // Distance
    // ------------------------------------------------------------------

    public function shippingDistance(array $origin, array $destination): float
    {
        return $this->shippingDistanceWithFlag($origin, $destination)['distance'];
    }

    /**
     * Same as shippingDistance() but also reports whether either endpoint fell
     * back to country-level coordinates (display-only info, cost is unaffected
     * for international shipments which price by country/region rate).
     *
     * @return array{distance: float, estimated: bool}
     */
    public function shippingDistanceWithFlag(array $origin, array $destination): array
    {
        $originCoordinates = $this->resolveCoordinates($origin, 'origin');
        $destinationCoordinates = $this->resolveCoordinates($destination, 'destination');

        if ($originCoordinates === null || $destinationCoordinates === null) {
            return ['distance' => 0.0, 'estimated' => false];
        }

        $originLat = (float) $originCoordinates['latitude'];
        $originLon = (float) $originCoordinates['longitude'];
        $destLat = (float) $destinationCoordinates['latitude'];
        $destLon = (float) $destinationCoordinates['longitude'];

        $estimated = ($originCoordinates['fallback'] ?? false)
            || ($destinationCoordinates['fallback'] ?? false);

        $settings = $this->settings();
        if ($settings && $settings->enable_routing && $settings->routing_provider === 'osrm') {
            $distance = $this->routingDistance($originLat, $originLon, $destLat, $destLon);
            if ($distance !== null) {
                return ['distance' => round($distance, 2), 'estimated' => $estimated];
            }
        }

        return ['distance' => round($this->haversineKm($originLat, $originLon, $destLat, $destLon), 2), 'estimated' => $estimated];
    }

    protected function resolveCoordinates(array $location, string $label): ?array
    {
        $latitude = (float) ($location['latitude'] ?? 0);
        $longitude = (float) ($location['longitude'] ?? 0);

        if ($this->hasValidCoordinates($latitude, $longitude)) {
            return ['latitude' => $latitude, 'longitude' => $longitude];
        }

        // Progressive fallback: most specific first, country-only last.
        // Postal code is deliberately EXCLUDED: a domestic postal code
        // appended to a foreign city (e.g. "Austin ... 40123") makes
        // Nominatim return zero results.
        foreach ($this->buildGeocodeCandidates($location) as $candidate) {
            $coordinates = $this->geocode($candidate);

            if ($coordinates !== null) {
                return $coordinates;
            }
        }

        // Offline safety net so an international distance is never
        // silently reported as 0 km when geocoding yields nothing.
        $fallback = $this->countryFallbackCoordinates((string) ($location['country'] ?? ''));

        if ($fallback !== null) {
            Log::info('[Shipping] Using country fallback coordinates', [
                'location' => $label,
                'country' => $location['country'] ?? null,
            ]);

            return [...$fallback, 'fallback' => true];
        }

        Log::warning('[Shipping] Coordinates unavailable after geocoding fallback', [
            'location' => $label,
            'query' => implode(' | ', $this->buildGeocodeCandidates($location)),
        ]);

        return null;
    }

    /**
     * Ordered geocode candidates, most specific first.
     *
     * @return list<string>
     */
    protected function buildGeocodeCandidates(array $location): array
    {
        $district = trim((string) ($location['district'] ?? ''));
        $city = trim((string) ($location['city'] ?? ''));
        $state = trim((string) ($location['state'] ?? ''));
        $country = trim((string) ($location['country'] ?? ''));

        $candidates = [];

        if ($district !== '' && $city !== '' && $state !== '' && $country !== '') {
            $candidates[] = "{$district} {$city} {$state} {$country}";
        }

        if ($city !== '' && $state !== '' && $country !== '') {
            $candidates[] = "{$city} {$state} {$country}";
        }

        if ($city !== '' && $country !== '') {
            $candidates[] = "{$city} {$country}";
        }

        if ($state !== '' && $country !== '') {
            $candidates[] = "{$state} {$country}";
        }

        if ($country !== '') {
            $candidates[] = $country;
        }

        return array_values(array_unique(array_filter(array_map('trim', $candidates))));
    }

    /**
     * Approximate country-centre coordinates (offline fallback).
     *
     * @return array{latitude: float, longitude: float}|null
     */
    protected function countryFallbackCoordinates(string $country): ?array
    {
        $key = mb_strtolower(trim($country));

        return self::COUNTRY_COORDINATES[$key] ?? null;
    }

    /**
     * Priority: road/driving distance via routing API, else straight-line Haversine.
     */
    public function routingDistance(float $originLat, float $originLon, float $destLat, float $destLon): ?float
    {
        $key = config('services.osrm.mode', 'driving');
        $baseUrl = config('services.osrm.endpoint');

        if (empty($baseUrl)) {
            return null;
        }

        try {
            $url = rtrim($baseUrl, '/')."/route/v1/{$key}/{$originLon},{$originLat};{$destLon},{$destLat}";
            $response = Http::timeout(5)->get($url, ['overview' => 'false']);

            if ($response->ok() && $response->json('code') === 'Ok') {
                $distanceMeters = (float) ($response->json('routes.0.distance') ?? 0);
                if ($distanceMeters > 0) {
                    return $distanceMeters / 1000;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[Shipping] OSRM routing failed, falling back to Haversine', [
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    public function haversineKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) ** 2
           + cos($lat1) * cos($lat2) * sin($dlon / 2) ** 2;

        return 2 * self::EARTH_RADIUS_KM * asin(sqrt($a));
    }

    // ------------------------------------------------------------------
    // Zone / rates
    // ------------------------------------------------------------------

    public function determineShippingZone(float $distance): ?ShippingZone
    {
        return ShippingZone::query()
            ->where('active', true)
            ->where('min_distance_km', '<=', $distance)
            ->where(function ($q) use ($distance) {
                $q->whereNull('max_distance_km')
                    ->orWhere('max_distance_km', '>=', $distance);
            })
            ->orderBy('min_distance_km')
            ->first();
    }

    public function lookupCountry(string $countryName): ?ShippingCountry
    {
        return ShippingCountry::query()
            ->with('region')
            ->where('active', true)
            ->whereRaw('LOWER(country) = ?', [strtolower(trim($countryName))])
            ->first();
    }

    public function defaultCourier(string $shippingType): ?string
    {
        $courier = ShippingCourier::query()
            ->where('active', true)
            ->where('type', $shippingType)
            ->first();

        return $courier?->name;
    }

    // ------------------------------------------------------------------
    // Origin / validation helpers
    // ------------------------------------------------------------------

    public function defaultOrigin(): array
    {
        $settings = $this->settings();

        return [
            'country' => $settings?->origin_country ?? 'Indonesia',
            'city' => $settings?->origin_city,
            'latitude' => $settings ? (float) $settings->origin_latitude : 0.0,
            'longitude' => $settings ? (float) $settings->origin_longitude : 0.0,
        ];
    }

    public function settings(): ?ShippingSetting
    {
        return ShippingSetting::query()->latest('id')->first();
    }

    public function hasValidCoordinates(float $latitude, float $longitude): bool
    {
        return $latitude >= -90 && $latitude <= 90
            && $longitude >= -180 && $longitude <= 180
            && ! ($latitude == 0 && $longitude == 0);
    }

    // ------------------------------------------------------------------
    // Geocoding (server-side only)
    // ------------------------------------------------------------------

    /**
     * Resolve a human-readable destination ("City, State, Country") to
     * geographic coordinates via the configured geocoding endpoint.
     * Returns null when no endpoint is configured or the lookup fails so
     * callers always have a graceful fallback. Successful lookups are cached
     * for 30 days; recent misses for 1 hour (respects Nominatim usage policy
     * and keeps repeated checkout estimates instant).
     *
     * @return array{latitude: float, longitude: float}|null
     */
    public function geocode(string $query): ?array
    {
        $endpoint = config('services.nominatim.endpoint');

        if (empty($endpoint)) {
            return null;
        }

        foreach ($this->geocodeQueries($query) as $candidate) {
            $cacheKey = 'shipping.geocode.'.md5(mb_strtolower(trim($candidate)));

            $hit = Cache::get($cacheKey);

            if (is_array($hit) && isset($hit['latitude'], $hit['longitude'])) {
                return $hit;
            }

            if ($hit === 'MISS') {
                continue;
            }

            try {
                $response = Http::timeout(6)
                    ->withHeaders([
                        'User-Agent' => config('services.nominatim.user_agent', 'ALGO NATION'),
                        'Accept' => 'application/json',
                    ])
                    ->get($endpoint, [
                        'q' => $candidate,
                        'format' => 'json',
                        'limit' => 1,
                    ]);

                $data = $response->ok() ? $response->json() : null;

                if (is_array($data) && isset($data[0]['lat'], $data[0]['lon'])) {
                    $lat = (float) $data[0]['lat'];
                    $lon = (float) $data[0]['lon'];

                    if ($this->hasValidCoordinates($lat, $lon)) {
                        $coordinates = ['latitude' => $lat, 'longitude' => $lon];
                        Cache::put($cacheKey, $coordinates, now()->addDays(30));

                        return $coordinates;
                    }
                }

                Cache::put($cacheKey, 'MISS', now()->addHour());
            } catch (\Throwable $e) {
                Log::warning('[Shipping] Geocoding failed', [
                    'query' => $candidate,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return null;
    }

    /**
     * Nominatim may not understand the localized country names stored in
     * addresses, so retry with their common English names.
     *
     * @return list<string>
     */
    protected function geocodeQueries(string $query): array
    {
        $queries = [trim($query)];

        foreach (self::COUNTRY_ALIASES as $localized => $english) {
            if (stripos($query, $localized) !== false) {
                $queries[] = trim(str_ireplace($localized, $english, $query));
            }
        }

        return array_values(array_unique(array_filter($queries)));
    }

    // ------------------------------------------------------------------
    // Legacy admin shipping-status lifecycle (kept for OrderController)
    // ------------------------------------------------------------------

    public function validateTransition(Transaction $transaction, string $newStatus): ?string
    {
        if ($newStatus === 'pesanan_diterima'
            && ! in_array($transaction->shipping_status, ['sedang_diantar', 'pesanan_diterima'])) {
            return 'Pesanan tidak dapat ditandai sebagai diterima karena belum dalam status Sedang Diantar.';
        }

        if ($newStatus === 'dibatalkan' && $transaction->payment_status !== 'paid') {
            return 'Pesanan yang belum dibayar tidak dapat dibatalkan dari sini.';
        }

        if ($transaction->status === 'pending_payment' || $transaction->payment_status !== 'paid') {
            return 'Pesanan yang belum dibayar tidak dapat diproses pengirimannya.';
        }

        return null;
    }

    public function resolveUpdate(Transaction $transaction, array $input): array
    {
        $update = [
            'shipping_courier' => $input['shipping_courier'] ?? $transaction->shipping_courier,
            'tracking_number' => $input['tracking_number'] ?? $transaction->tracking_number,
            'shipping_status' => $input['shipping_status'],
            'estimated_delivery_start' => $input['estimated_delivery_start'] ?? $transaction->estimated_delivery_start,
            'estimated_delivery_end' => $input['estimated_delivery_end'] ?? $transaction->estimated_delivery_end,
            'shipping_updated_at' => now(),
        ];

        $status = $input['shipping_status'];

        if (! empty($input['shipped_at'])) {
            $update['shipped_at'] = $input['shipped_at'];
        } elseif (in_array($status, ['diserahkan_ke_kurir', 'dalam_perjalanan', 'tiba_di_kota_tujuan', 'sedang_diantar', 'pesanan_diterima'])
            && ! $transaction->shipped_at) {
            $update['shipped_at'] = now();
        }

        if ($status === 'pesanan_diterima' && ! $transaction->delivered_at) {
            $update['delivered_at'] = now();
            $update['status'] = 'completed';
        }

        if ($status === 'dibatalkan') {
            $update['status'] = 'cancelled';
        }

        return $update;
    }
}
