<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\FlashSale;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
    ) {
    }

    /**
     * Return the current cart state from the session.
     */
    public function state(Request $request): JsonResponse
    {
        $cart = $request->session()->get('cart', []);

        return response()->json($this->cartService->toArray($cart));
    }

    /**
     * Add an item (or variant) to the cart.
     */
    public function add(Request $request): JsonResponse
    {
        FlashSale::syncAllStatuses();

        if (! Auth::check()) {
            return response()->json([
                'success'  => false,
                'message'  => 'Silakan login terlebih dahulu untuk menambahkan produk.',
                'redirect' => route('login'),
            ], 401);
        }

        $data = $request->validate([
            'product_id'    => ['required', 'integer', 'exists:products,id'],
            'variant_id'    => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity'      => ['required', 'integer', 'min:1', 'max:99'],
            'flash_sale_id' => ['nullable', 'integer', 'exists:flash_sales,id'],
        ]);

        $product = Product::with('variants', 'activeFlashSale')->findOrFail($data['product_id']);

        $variantId = $data['variant_id'] ?? null;
        $variant   = $variantId ? $product->variants->firstWhere('id', $variantId) : null;

        // The cart is server-authoritative about flash-sale pricing: when the
        // product currently has an active flash sale, that price is ALWAYS used
        // regardless of whether the client sent a flash_sale_id. This keeps the
        // price consistent across every page (shop, featured, landing, detail).
        $flashSale = $product->activeFlashSale;

        $price = (float) ($flashSale?->sale_price ?? $variant?->price ?? $product->price);
        $stock = $flashSale ? min($flashSale->stock, $variant?->stock ?? $product->stock) : ($variant?->stock ?? $product->stock);

        if ($stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak tersedia (stok habis).',
            ], 422);
        }

        $quantity = min((int) $data['quantity'], max(1, $stock));

        $cart = $request->session()->get('cart', []);

        $cartKey = $product->id.($variantId ? '-'.$variantId : '');

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = min($cart[$cartKey]['quantity'] + $quantity, $stock);
        } else {
            $cart[$cartKey] = [
                'key'           => $cartKey,
                'product_id'    => $product->id,
                'variant_id'    => $variantId,
                'name'          => $product->name,
                'variant'       => $variant?->name,
                'image'         => $product->image_url,
                'price'         => $price,
                'quantity'      => $quantity,
                'stock'         => $stock,
                'category'      => $product->category,
                'flash_sale_id' => $flashSale?->id,
            ];
        }

        $request->session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Produk ditambahkan ke keranjang!',
            'cart'    => $this->cartService->toArray($cart),
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
            'cart'    => $this->cartService->toArray($cart),
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
            'cart'    => $this->cartService->toArray($cart),
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
            'cart'    => $this->cartService->toArray([]),
        ]);
    }
}