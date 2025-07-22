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
     * Summary of getUserCart
       * Get all cart items for the authenticated user.
     * @return \Illuminate\Database\Eloquent\Collection<int, Cart>
     */

    public function getUserCart()
    {
        return Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();
    }

    /**
     * Summary of addToCart
     * Add a product to the user's cart.
     * @param mixed $productId
     * @param mixed $quantity
     * @throws \Exception
     * @return void
     */
    public function addToCart($productId, $quantity)
{
  $product=Product::findOrFail($productId);
  //checking if stock is availible
  if ($product->stock <$quantity) {
    throw new \Exception("Only {$product->stock} items are available in stock.");
  }
  $cartItem= Cart::where('user_id',Auth::id())->where('product_id',$productId)->first();
  if ($cartItem) {
    $newQuantity=$cartItem>$quantity+$quantity;
    if ($product->stock<$newQuantity) {
        throw new \Exception("You already have {$cartItem->quantity}in cart.Only {$product->stock} items are available.");
    }
    $cartItem->quantity=$newQuantity;
    $cartItem->save();

  }
  else{
    Cart::create(
        [
            'user_id'=>Auth::id(),
            'product_id'=>$productId,
            'quantity'=>$quantity,
            'price'=>$product->price
        ]
        );
  }
}


    /**
     * Summary of removeFromCart
     * Remove item from cart by ID.
     * @param mixed $id
     * @return void
     */
    public function removeFromCart($id)
    {
        Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();
    }

    /**
     * Summary of getTotal
     * Get total price of the cart.
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
     * Summary of clearCart
     * Clear all cart items for the user.
     * @return void
     */
    public function clearCart()
    {
        Cart::where('user_id', Auth::id())->delete();
    }


    /**
     * Summary of placeOrder
     * Place an order for the user with the selected address.
     * @param int $userId
     * @param int $addressId
     */
    public function placeOrder(int $userId, int $addressId)
    {
        return DB::transaction(function () use ($userId, $addressId) {
            $cartItems = Cart::with('product')->where('user_id', $userId)->get();

            if ($cartItems->isEmpty()) {
                // No cart items, cannot place order
                return null;
            }
            //check if there's stock left first
          foreach ($cartItems as $item) {
            if ($item->quantity> $item->product->stock) {
                throw new \Exception(
                    "There's no more stock left more than {$item->quantity}. "."Please adjust your cart quantity or remove the item."
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
            //create order items & reduce stocks
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order['id'],
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
                //reduce the amount of stocks accordiing to the quantities customers chose
                $item->product->decrement('stock',$item->quantity);
            }

            Cart::where('user_id', $userId)->delete();
            //admin/superadmin dashboard getting notifications
            $admins= User::role(['admin','superadmin'])->get();
            foreach($admins as $admin){
                $admin->notify(new NewOrderNotification($order));
            }
            return $order;
        });
    }
}
