<?php
namespace App\Services;

class CartService{
    public function dropdownPreview($cartItems){
        $total=$cartItems->sum(fn($item)=>$item->product->price*$item->quantity);
        return[
            'items'=>$cartItems,
            'total'=>$total,
        ];
    }
}
