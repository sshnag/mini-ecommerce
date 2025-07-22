<?php
namespace App\Services;

class OrderService{
    /**
     * Sum of the total price of the items from cart
     * @param mixed $cartItems
     */
    public function calculatedTotal($cartItems){
        return $cartItems->sum(fn($item)=>$item->product->price*$item->quantity());
    }
}
