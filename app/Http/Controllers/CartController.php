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

        $product = Product::with('variants', 'flashSale', 'activeFlashSale')->findOrFail($data['product_id']);

        $variantId = $data['variant_id'] ?? null;
        $variant   = $variantId ? $product->variants->firstWhere('id', $variantId) : null;

        // Satu sumber harga: $product->priceForVariant($variant).
        // Flash Sale aktif (status=active + now between start/end) selalu menang,
        // harga asli tidak pernah dimutasi, dan Produk Unggulan memakai logika
        // yang sama via $product->final_price.
        $flashSale = $product->flash_sale_price !== null
            ? ($product->relationLoaded('activeFlashSale') ? $product->getRelation('activeFlashSale') : $product->activeFlashSale)
            : null;
        $flashSale = $flashSale ?? ($product->relationLoaded('flashSale') ? $product->getRelation('flashSale') : null);
        $flashSale = $flashSale && $flashSale->isActive() ? $flashSale : null;

        // Bila stok flash sale habis, kembali ke harga & stok normal agar
        // produk tetap bisa dibeli (harga ikut revert otomatis).
        $useFlashPrice = $flashSale && $flashSale->stock > 0;

        $price = $useFlashPrice ? (float) $flashSale->sale_price : $product->priceForVariant($variant);
        if ($useFlashPrice) {
            // priceForVariant sudah mengembalikan sale price saat aktif;
            // pastikan sama persis dengan relasi yang dipakai.
            $price = (float) $flashSale->sale_price;
        }
        $baseStock = $variant?->stock ?? $product->stock;
        $stock = $useFlashPrice ? min($flashSale->stock, $baseStock) : $baseStock;

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
                'variant'       => $variant?->display_name ?? $variant?->name,
                'image'         => $variant?->image_url ?? $product->image_url,
                'price'         => $price,
                'quantity'      => $quantity,
                'stock'         => $stock,
                'category'      => $product->category,
                'flash_sale_id' => $useFlashPrice ? $flashSale->id : null,
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