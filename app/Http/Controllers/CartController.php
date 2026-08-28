<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Return the current cart state from the session.
     */
    public function state(Request $request): JsonResponse
    {
        $cart = $request->session()->get('cart', []);

        return response()->json([
            'items'       => array_values($cart),
            'count'       => $this->count($cart),
            'subtotal'    => $this->subtotal($cart),
            'total'       => $this->total($cart),
        ]);
    }

    /**
     * Add an item (or variant) to the cart.
     */
    public function add(Request $request): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'success'  => false,
                'message'  => 'Silakan login terlebih dahulu untuk menambahkan produk.',
                'redirect' => route('login'),
            ], 401);
        }

        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::with('variants')->findOrFail($data['product_id']);

        $variantId = $data['variant_id'] ?? null;
        $variant   = $variantId ? $product->variants->firstWhere('id', $variantId) : null;

        $price  = (float) ($variant->price ?? $product->price);
        $name   = $product->name;
        $stock  = $variant?->stock ?? $product->stock;

        $quantity = min((int) $data['quantity'], max(1, $stock));

        if ($stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak tersedia (stok habis).',
            ], 422);
        }

        $cart = $request->session()->get('cart', []);

        $key = $product->id.($variantId ? '-'.$variantId : '');

        if (isset($cart[$key])) {
            $newQty = min($cart[$key]['quantity'] + $quantity, $stock);
            $cart[$key]['quantity'] = $newQty;
        } else {
            $cart[$key] = [
                'key'        => $key,
                'product_id' => $product->id,
                'variant_id' => $variantId,
                'name'       => $name,
                'variant'    => $variant?->name,
                'image'      => $product->image_url,
                'price'      => $price,
                'quantity'   => $quantity,
                'stock'      => $stock,
                'category'   => $product->category,
            ];
        }

        $request->session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Produk ditambahkan ke keranjang!',
            'cart'    => [
                'items'    => array_values($cart),
                'count'    => $this->count($cart),
                'subtotal' => $this->subtotal($cart),
                'total'    => $this->total($cart),
            ],
        ]);
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'key'      => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $request->session()->get('cart', []);

        if (! isset($cart[$data['key']])) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan di keranjang.',
            ], 404);
        }

        $cart[$data['key']]['quantity'] = min((int) $data['quantity'], (int) $cart[$data['key']]['stock']);
        $request->session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Keranjang diperbarui.',
            'cart'    => [
                'items'    => array_values($cart),
                'count'    => $this->count($cart),
                'subtotal' => $this->subtotal($cart),
                'total'    => $this->total($cart),
            ],
        ]);
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request): JsonResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string'],
        ]);

        $cart = $request->session()->get('cart', []);

        Arr::forget($cart, $data['key']);
        $request->session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Item dihapus dari keranjang.',
            'cart'    => [
                'items'    => array_values($cart),
                'count'    => $this->count($cart),
                'subtotal' => $this->subtotal($cart),
                'total'    => $this->total($cart),
            ],
        ]);
    }

    /**
     * Clear the whole cart.
     */
    public function clear(Request $request): JsonResponse
    {
        $request->session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Keranjang dikosongkan.',
            'cart'    => ['items' => [], 'count' => 0, 'subtotal' => 0, 'total' => 0],
        ]);
    }

    /**
     * Total number of items.
     */
    protected function count(array $cart): int
    {
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Subtotal without shipping.
     */
    protected function subtotal(array $cart): float
    {
        return round(array_sum(array_map(
            fn ($item) => $item['price'] * $item['quantity'],
            $cart
        )), 2);
    }

    /**
     * Total (subtotal + delivery fee).
     */
    protected function total(array $cart): float
    {
        return round($this->subtotal($cart) + $this->shippingCost($this->subtotal($cart)), 2);
    }

    /**
     * Flat-rate delivery estimate: free above Rp 500.000, otherwise Rp 25.000.
     */
    public static function shippingCost(float $subtotal): float
    {
        return $subtotal >= 500000 ? 0 : 25000;
    }

    /**
     * Estimated delivery time in days based on subtotal / city.
     */
    public static function estimatedDays(string $city = ''): int
    {
        return in_array(strtolower(trim($city)), ['jakarta', 'bandung', 'surabaya', 'yogyakarta', 'semarang'])
            ? 2
            : 4;
    }
}

