<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory, SoftDeletes;

    //relation with Order Item model
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    //relation with User Models
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //relation with Address model
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    //relation with Payment model
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    protected $guarded = [];

    protected $fillable = [
        'user_id',
        'address_id',
        'status',
        'custom_id',
        'total_amount',

    ];
    public function getRouteKeyName()
    {
        return 'id';
    }
}
