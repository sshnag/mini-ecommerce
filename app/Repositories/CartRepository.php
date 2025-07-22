<?php
namespace App\Repositories;

use App\Models\Cart;
class CartRepository{
    /**
     * Summary of getUserCart
     * getting user id with cart data
     * @param mixed $userId
     * @return \Illuminate\Database\Eloquent\Collection<int, Cart>
     */
    public function getUserCart($userId){
        return Cart::with('product')->where('user_id',$userId)->get();
    }
    /**
     * Summary of addToCart
     * Addin/creasting in cart model
     * @param array $data
     * @return Cart
     */
    public function addToCart(array $data){
        return Cart::create($data);
    }
    /**
     *
     * Delete(Archieve)cart data
     * @param mixed $userId
     */
    public function clearCart($userId){
        return Cart::where('user_id',$userId)->delete();
    }
    /**
     *removing items from cart
     * @param mixed $cartId
     * @return bool|null
     */
    public function removeItem($cartId){
        return Cart::find($cartId)?->delete();
    }
}
