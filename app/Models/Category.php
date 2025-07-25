<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'size_type', 'default_sizes'];
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    //relation with Product
    public function products()
{
    return $this->hasMany(Product::class);
}


    protected $casts = [
        'default_sizes' => 'array',
    ];
    public function getRouteKeyName()
{
    return 'slug';
}
    //relation with Review Model
public function reviews()
{
    return $this->hasMany(Review::class);
}
    public static function getSizePresets(){
        return[
            'ring'=>['4','4.5','5','5.5','6','6.5','7','7.5','8'],
            'bracelet'=>['Small','Medium','Large','XL']
        ];
    }
}
