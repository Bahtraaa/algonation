<?php

namespace App\Services;

use Illuminate\Support\Arr;

/**
 * Cart domain logic: totals, shipping estimates and the payload shape
 * returned to every cart endpoint.
 */
class CartService
{
    /**
     * Total number of items across every cart line.
     */
    public function count(array $cart): int
    {
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Subtotal without shipping.
     */
    public function subtotal(array $cart): float
    {
        return round(array_sum(array_map(
            fn ($cartItem) => $cartItem['price'] * $cartItem['quantity'],
            $cart
        )), 2);
    }

    /**
     * Total payable amount (subtotal + shipping).
     *
     * Shipping is computed by the ShippingService for a real destination at
     * checkout, so when no destination is known yet the total is just the
     * subtotal (the cart drawer shows "dihitung saat checkout").
     */
    public function total(array $cart): float
    {
        return round($this->subtotal($cart), 2);
    }

    /**
     * Build the normalized cart payload used by every cart endpoint.
     *
     * @param  array<string, array<string, mixed>>  $cart
     * @return array<string, mixed>
     */
    public function toArray(array $cart): array
    {
        return [
            'items'    => array_values($cart),
            'count'    => $this->count($cart),
            'subtotal' => $this->subtotal($cart),
            'total'    => $this->total($cart),
        ];
    }
}