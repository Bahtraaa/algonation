<?php

use App\Services\ShippingService;
use Illuminate\Support\Facades\Http;

it('calculates distance for overseas origin when origin coordinates are missing', function () {
    Http::fake([
        'https://nominatim.openstreetmap.org/search*' => Http::response([
            ['lat' => '1.3521', 'lon' => '103.8198'],
        ], 200),
    ]);

    $service = new ShippingService();

    $origin = [
        'country' => 'Singapore',
        'city' => 'Singapore',
        'latitude' => 0.0,
        'longitude' => 0.0,
    ];

    $destination = [
        'country' => 'Indonesia',
        'city' => 'Jakarta',
        'latitude' => -6.2088,
        'longitude' => 106.8456,
    ];

    $distance = $service->shippingDistance($origin, $destination);

    expect($distance)->toBeGreaterThan(0)
        ->and($distance)->toBeFloat();
});

it('geocodes localized international country names with an English fallback', function () {
    Http::fake(function ($request) {
        return $request['q'] === 'Austin Texas, Amerika Serikat'
            ? Http::response([], 200)
            : Http::response([
                ['lat' => '30.2672', 'lon' => '-97.7431'],
            ], 200);
    });

    $coordinates = (new ShippingService)->geocode('Austin Texas, Amerika Serikat');

    expect($coordinates)->toBe([
        'latitude' => 30.2672,
        'longitude' => -97.7431,
    ]);
});
