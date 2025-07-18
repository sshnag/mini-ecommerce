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
use App\Models\User;class CartService
{
    /**
     * Get all cart items for the authenticated user.
     */
    public function getUserCart()
    {
        return Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();
    }

    /**
     * Add a product to the user's cart.
     */
    public function addToCart($productId, $quantity)
{
    $cartItem = Cart::where('user_id', Auth::id())
        ->where('product_id', $productId)
        ->first();

    if ($cartItem) {
        $cartItem->quantity = $quantity;
        $cartItem->save();
    } else {
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);
    }
}


    /**
     * Remove item from cart by ID.
     */
    public function removeFromCart($id)
    {
        Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();
    }

    /**
     * Get total price of the cart.
     */
    public function getTotal()
    {
        $cartItems = $this->getUserCart();

        return $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    /**
     * Clear all cart items for the user.
     */
    public function clearCart()
    {
        Cart::where('user_id', Auth::id())->delete();
    }

    /**
     * Dropdown preview data (cart summary).
     */
    public function getDropdownPreview()
    {
        $cartItems = $this->getUserCart();
        $total = $this->getTotal();

        return [
            'cartItems' => $cartItems,
            'total' => $total,
        ];
    }
    /**
     * Place an order for the user with the selected address.
     *
     * @param int $userId
     * @param int $addressId
     * @return Order|null
     */
    public function placeOrder(int $userId, int $addressId)
    {
        return DB::transaction(function () use ($userId, $addressId) {
            $cartItems = Cart::with('product')->where('user_id', $userId)->get();

            if ($cartItems->isEmpty()) {
                // No cart items, cannot place order
                return null;
            }

            $total = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $order = Order::create([
                'user_id' => $userId,
                'address_id' => $addressId,
'total_amount' => $total,
                'status' => 'pending', // customize as needed
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            Cart::where('user_id', $userId)->delete();



            $admins= User::role(['admin','superadmin'])->get();
            foreach($admins as $admin){
                $admin->notify(new NewOrderNotification($order));
            }
            return $order;
        });
    }
}
