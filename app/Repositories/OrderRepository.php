<?php
namespace App\Repositories;

use App\Models\Order;
class OrderRepository{
    public function forUser($userId){
        return Order::with('orderItems.product')->where('user_id',$userId)->latest()->get();

    }

    public function create(array $data){
        return Order::create($data);

    }
}
