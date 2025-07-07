<?php
namespace App\Services;

class OrderService{
    public function calculatedTotal($cartItems){
        return $cartItems->sum(fn($item)=>$item->product->price*$item->quantity());
    }
}
