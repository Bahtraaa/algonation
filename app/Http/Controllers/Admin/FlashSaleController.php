<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FlashSaleController extends Controller
{
    public function index(): View
    {
        $flashSales = FlashSale::with('product')->latest()->get()->each->syncStatus();
        $products = Product::orderBy('name')->get();

        return view('admin.flash-sales.index', compact('flashSales', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $flashSale = FlashSale::create($this->validated($request));

        return back()->with('success', "Flash sale {$flashSale->product->name} berhasil ditambahkan.");
    }

    public function update(Request $request, FlashSale $flashSale): RedirectResponse
    {
        $flashSale->update($this->validated($request));

        return back()->with('success', 'Flash sale berhasil diperbarui.');
    }

    public function destroy(FlashSale $flashSale): RedirectResponse
    {
        $flashSale->delete();

        return back()->with('success', 'Flash sale berhasil dihapus.');
    }

    public function toggle(FlashSale $flashSale): RedirectResponse
    {
        $flashSale->syncStatus();
        $flashSale->update(['status' => $flashSale->status === 'inactive' ? 'scheduled' : 'inactive']);

        return back()->with('success', 'Status flash sale berhasil diperbarui.');
    }

    public function apiIndex(): JsonResponse
    {
        FlashSale::syncAllStatuses();

        $flashSales = FlashSale::active()->with('product')->latest()->get();

        return response()->json(['data' => $flashSales->map(fn (FlashSale $flashSale) => $this->resource($flashSale))]);
    }

    public function apiShow(FlashSale $flashSale): JsonResponse
    {
        $flashSale->load('product');
        $flashSale->syncStatus();

        return response()->json(['data' => $this->resource($flashSale)]);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'normal_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0', 'lte:normal_price'],
            'stock' => ['required', 'integer', 'min:0'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after:start_at'],
        ], [
            'sale_price.lte' => 'Harga flash sale tidak boleh lebih tinggi dari harga normal.',
            'end_at.after' => 'Waktu berakhir harus setelah waktu mulai.',
        ]);

        $data['start_at'] = Carbon::parse($data['start_at'], config('app.timezone'));
        $data['end_at'] = Carbon::parse($data['end_at'], config('app.timezone'));

        if ($data['end_at']->isPast()) {
            abort(422, 'Waktu berakhir tidak boleh berada di masa lalu.');
        }

        $data['discount_percentage'] = $data['normal_price'] > 0
            ? round((1 - ($data['sale_price'] / $data['normal_price'])) * 100, 2)
            : 0;
        $data['status'] = $request->route('flashSale')?->status === 'inactive' ? 'inactive' : 'scheduled';

        return $data;
    }

    private function resource(FlashSale $flashSale): array
    {
        return [
            'id' => $flashSale->id,
            'product_id' => $flashSale->product_id,
            'name' => $flashSale->product->name,
            'image' => $flashSale->product->image_url,
            'normal_price' => (float) $flashSale->normal_price,
            'sale_price' => (float) $flashSale->sale_price,
            'discount_percentage' => (float) $flashSale->discount_percentage,
            'stock' => $flashSale->stock,
            'start_at' => $flashSale->start_at->toIso8601String(),
            'end_at' => $flashSale->end_at->toIso8601String(),
            'status' => $flashSale->computed_status,
        ];
    }
}
