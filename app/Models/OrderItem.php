<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    //
    use HasFactory,SoftDeletes;
    //relation with Product model
    public function product(){
        return $this->belongsTo(Product::class);
    }
    //relation with Order model
    public function order(){
        return $this->belongsTo(Order::class);
    }
     protected $fillable = [
    'order_id',
    'product_id',
    'quantity',
    'price'
];
}
