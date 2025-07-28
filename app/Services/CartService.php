<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewOrderNotification;
use Spatie\Permission\Models\Role;
use App\Models\User;

class CartService
{
    /**
     * Get all cart items for the authenticated user or guest session
     * @return \Illuminate\Database\Eloquent\Collection<int, Cart>
     */
    public function getUserCart()
    {
        $query = Cart::with('product');

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', session()->getId());
        }

        return $query->get();
    }

    /**
     * Add a product to the user's cart
     * @param mixed $productId
     * @param mixed $quantity
     * @throws \Exception
     * @return void
     */
    public function addToCart($productId, $quantity)
    {
        $product = Product::findOrFail($productId);

        // Check stock availability
        if ($product->stock < $quantity) {
            throw new \Exception("Only {$product->stock} items are available in stock.");
        }

        $query = Cart::where('product_id', $productId);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', session()->getId());
        }

        $cartItem = $query->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->stock < $newQuantity) {
                throw new \Exception("You already have {$cartItem->quantity} in cart. Only {$product->stock} items are available.");
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'session_id' => Auth::check() ? null : session()->getId(),
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price
            ]);
        }
    }

    /**
     * Remove item from cart by ID
     * @param mixed $id
     * @return void
     */
    public function removeFromCart($id)
    {
        $query = Cart::where('id', $id);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', session()->getId());
        }

        $query->delete();
    }

    /**
     * Get total price of the cart
     * @return float|int|mixed
     */
    public function getTotal()
    {
        $cartItems = $this->getUserCart();
        return $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    /**
     * Clear all cart items for the user/session
     * @return void
     */
    public function clearCart()
    {
        $query = Cart::query();

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', session()->getId());
        }

        $query->delete();
    }

    /**
     * Place an order for the user with the selected address
     * @param int $userId
     * @param int $addressId
     */
    public function placeOrder(int $userId, int $addressId)
    {
        return DB::transaction(function () use ($userId, $addressId) {
            $cartItems = Cart::with('product')
                ->where('user_id', $userId)
                ->get();

            if ($cartItems->isEmpty()) {
                return null;
            }

            foreach ($cartItems as $item) {
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception(
                        "There's no more stock left more than {$item->quantity}. Please adjust your cart quantity or remove the item."
                    );
                }
            }

            $total = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $order = Order::create([
                'user_id' => $userId,
                'address_id' => $addressId,
                'total_amount' => $total,
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order['id'],
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            Cart::where('user_id', $userId)->delete();

            $admins = User::role(['admin','superadmin'])->get();
            foreach($admins as $admin) {
                $admin->notify(new NewOrderNotification($order));
            }

            return $order;
        });
    }
}
