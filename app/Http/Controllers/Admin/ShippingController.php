<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternationalRegion;
use App\Models\ShippingCountry;
use App\Models\ShippingCourier;
use App\Models\ShippingSetting;
use App\Models\ShippingZone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingController extends Controller
{
    /**
     * Shipping configuration dashboard: settings, zones, regions, countries, couriers.
     */
    public function index(): View
    {
        $settings = ShippingSetting::query()->latest('id')->first() ?? new ShippingSetting();
        $zones    = ShippingZone::orderBy('min_distance_km')->get();
        $regions  = InternationalRegion::with('countries')->orderBy('name')->get();
        $countries = ShippingCountry::with('region')->orderBy('country')->get();
        $couriers = ShippingCourier::orderBy('type')->orderBy('name')->get();

        return view('admin.shipping.index', compact(
            'settings',
            'zones',
            'regions',
            'countries',
            'couriers',
        ));
    }

    /**
     * Update the singleton store origin + routing config.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'origin_country'    => ['required', 'string', 'max:120'],
            'origin_city'       => ['nullable', 'string', 'max:120'],
            'origin_latitude'   => ['nullable', 'numeric', 'between:-90,90'],
            'origin_longitude'  => ['nullable', 'numeric', 'between:-180,180'],
            'routing_provider'  => ['nullable', 'string', 'in:none,osrm'],
            'enable_routing'    => ['nullable', 'boolean'],
        ]);

        $settings = ShippingSetting::query()->latest('id')->first();

        $data['enable_routing'] = $request->boolean('enable_routing');

        if ($settings) {
            $settings->update($data);
        } else {
            ShippingSetting::create($data);
        }

        return back()->with('success', 'Pengaturan pengiriman berhasil disimpan.');
    }

    // ---------------- Zones ----------------

    public function storeZone(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:120'],
            'min_distance_km'=> ['required', 'integer', 'min:0'],
            'max_distance_km'=> ['nullable', 'integer', 'gt:min_distance_km'],
            'rate_per_kg'    => ['required', 'numeric', 'min:0'],
            'min_charge'     => ['nullable', 'numeric', 'min:0'],
            'active'         => ['nullable', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        ShippingZone::create($data);

        return back()->with('success', 'Zona pengiriman berhasil ditambahkan.');
    }

    public function updateZone(Request $request, ShippingZone $zone): RedirectResponse
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:120'],
            'min_distance_km'=> ['required', 'integer', 'min:0'],
            'max_distance_km'=> ['nullable', 'integer', 'gt:min_distance_km'],
            'rate_per_kg'    => ['required', 'numeric', 'min:0'],
            'min_charge'     => ['nullable', 'numeric', 'min:0'],
            'active'         => ['nullable', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        $zone->update($data);

        return back()->with('success', 'Zona pengiriman berhasil diperbarui.');
    }

    public function destroyZone(ShippingZone $zone): RedirectResponse
    {
        $zone->delete();

        return back()->with('success', 'Zona pengiriman berhasil dihapus.');
    }

    // ---------------- International regions ----------------

    public function storeRegion(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:120', 'unique:international_regions,name'],
            'rate_per_kg' => ['nullable', 'numeric', 'min:0'],
            'min_charge'  => ['nullable', 'numeric', 'min:0'],
        ]);

        InternationalRegion::create($data);

        return back()->with('success', 'Region internasional berhasil ditambahkan.');
    }

    public function updateRegion(Request $request, InternationalRegion $region): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:120', 'unique:international_regions,name,'.$region->id],
            'rate_per_kg' => ['nullable', 'numeric', 'min:0'],
            'min_charge'  => ['nullable', 'numeric', 'min:0'],
        ]);

        $region->update($data);

        return back()->with('success', 'Region internasional berhasil diperbarui.');
    }

    public function destroyRegion(InternationalRegion $region): RedirectResponse
    {
        $region->delete();

        return back()->with('success', 'Region internasional berhasil dihapus.');
    }

    // ---------------- Countries ----------------

    public function storeCountry(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'region_id'   => ['required', 'integer', 'exists:international_regions,id'],
            'country'     => ['required', 'string', 'max:120', 'unique:shipping_countries,country'],
            'rate_per_kg' => ['nullable', 'numeric', 'min:0'],
            'min_charge'  => ['nullable', 'numeric', 'min:0'],
            'active'      => ['nullable', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        ShippingCountry::create($data);

        return back()->with('success', 'Negara tujuan berhasil ditambahkan.');
    }

    public function updateCountry(Request $request, ShippingCountry $country): RedirectResponse
    {
        $data = $request->validate([
            'region_id'   => ['required', 'integer', 'exists:international_regions,id'],
            'country'     => ['required', 'string', 'max:120', 'unique:shipping_countries,country,'.$country->id],
            'rate_per_kg' => ['nullable', 'numeric', 'min:0'],
            'min_charge'  => ['nullable', 'numeric', 'min:0'],
            'active'      => ['nullable', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        $country->update($data);

        return back()->with('success', 'Negara tujuan berhasil diperbarui.');
    }

    public function destroyCountry(ShippingCountry $country): RedirectResponse
    {
        $country->delete();

        return back()->with('success', 'Negara tujuan berhasil dihapus.');
    }

    // ---------------- Couriers ----------------

    public function storeCourier(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:120'],
            'type'  => ['required', 'string', 'in:domestic,international'],
            'active'=> ['nullable', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        ShippingCourier::create($data);

        return back()->with('success', 'Layanan pengiriman berhasil ditambahkan.');
    }

    public function destroyCourier(ShippingCourier $courier): RedirectResponse
    {
        $courier->delete();

        return back()->with('success', 'Layanan pengiriman berhasil dihapus.');
    }
}