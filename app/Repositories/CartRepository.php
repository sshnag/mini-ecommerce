<?php
namespace App\Repositories;

use App\Models\Cart;
class CartRepository{
    public function getUserCart($userId){
        return Cart::with('product')->where('user_id',$userId)->get();
    }
    public function addToCart(array $data){
        return Cart::create($data);
    }
    public function clearCart($userId){
        return Cart::where('user_id',$userId)->delete();
    }
    public function removeItem($cartId){
        return Cart::find($cartId)?->delete();
    }
}
