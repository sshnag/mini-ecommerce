<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory,SoftDeletes;
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
      public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    // In Order.php model
public function payment()
{
    return $this->hasOne(Payment::class);
}

    protected $fillable = [
    'user_id',
    'address_id',
    'status',
    'custom_id',
    'total_amount',

];
}
