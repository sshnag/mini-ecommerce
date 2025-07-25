<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory,SoftDeletes;

    //
    protected $fillable=[
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];
    //relation with user model
    public function user(){
        return $this->belongsTo(User::class);

    }
    //relation with Product model
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
