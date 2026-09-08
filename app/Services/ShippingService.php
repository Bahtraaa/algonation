<?php

namespace App\Services;

use App\Models\ShippingCountry;
use App\Models\ShippingCourier;
use App\Models\ShippingSetting;
use App\Models\ShippingZone;
use App\Models\Transaction;
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

        $distance = $this->shippingDistance($origin, $destination);

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
        $originLat = (float) ($origin['latitude'] ?? 0);
        $originLon = (float) ($origin['longitude'] ?? 0);
        $destLat = (float) ($destination['latitude'] ?? 0);
        $destLon = (float) ($destination['longitude'] ?? 0);

        if ($this->hasValidCoordinates($originLat, $originLon) && $this->hasValidCoordinates($destLat, $destLon)) {
            $settings = $this->settings();
            if ($settings && $settings->enable_routing && $settings->routing_provider === 'osrm') {
                $distance = $this->routingDistance($originLat, $originLon, $destLat, $destLon);
                if ($distance !== null) {
                    return round($distance, 2);
                }
            }

            return round($this->haversineKm($originLat, $originLon, $destLat, $destLon), 2);
        }

        return 0.0;
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
     * callers always have a graceful fallback.
     *
     * @return array{latitude: float, longitude: float}|null
     */
    public function geocode(string $query): ?array
    {
        $endpoint = config('services.nominatim.endpoint');

        if (empty($endpoint)) {
            return null;
        }

        try {
            $response = Http::timeout(6)
                ->withHeaders([
                    'User-Agent' => config('services.nominatim.user_agent', 'ALGO NATION'),
                    'Accept' => 'application/json',
                ])
                ->get($endpoint, [
                    'q' => $query,
                    'format' => 'json',
                    'limit' => 1,
                ]);

            $data = $response->json();

            if (is_array($data) && isset($data[0]['lat'], $data[0]['lon'])) {
                $lat = (float) $data[0]['lat'];
                $lon = (float) $data[0]['lon'];

                if ($this->hasValidCoordinates($lat, $lon)) {
                    return ['latitude' => $lat, 'longitude' => $lon];
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[Shipping] Geocoding failed', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
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
