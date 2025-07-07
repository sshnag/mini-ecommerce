<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Get the authenticated user's cart items.
     */
    public function getUserCart()
    {
        return Cart::with('product')->where('user_id', Auth::id())->get();
    }

    /**
     * Add item to cart with optional size check (ring/bracelet).
     */
    public function addToCart(string $productId, int $quantity = 1, ?string $size = null): void
    {
        $existing = Cart::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->when($size, fn($q) => $q->where('size', $size))
            ->first();

        if ($existing) {
            $existing->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id'    => Auth::id(),
                'product_id' => $productId,
                'quantity'   => $quantity,
                'size'       => $size,
            ]);
        }
    }

    /**
     * Remove a specific cart item.
     */
    public function removeFromCart(int $cartId): void
    {
        Cart::where('id', $cartId)->where('user_id', Auth::id())->delete();
    }

    /**
     * Clear the authenticated user’s cart.
     */
    public function clearCart(): void
    {
        Cart::where('user_id', Auth::id())->delete();
    }

    /**
     * Return dropdown preview data (Tiffany style).
     */
    public function getDropdownPreview(): array
    {
        $items = $this->getUserCart();
        $total = $items->sum(fn($item) => $item->product->price * $item->quantity);
        $count = $items->sum('quantity');

        return compact('items', 'total', 'count');
    }

    /**
     * Get total price of cart.
     */
    public function getTotal(): float
    {
        return $this->getUserCart()->sum(fn($item) => $item->product->price * $item->quantity);
    }
}
