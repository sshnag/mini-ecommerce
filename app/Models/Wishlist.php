<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    //
    use HasFactory;
    protected $table= 'wishlists';
    protected $fillable=[
        'user_id',
        'session_id',
        'product_id',
    ];
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
