<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    public function products()
{
    return $this->hasMany(Product::class);
}

    public static function getSizePresets(){
        return[
            'ring'=>['4','4.5','5','5.5','6','6.5','7','7.5','8'],
            'bracelet'=>['Small','Medium','Large','XL']
        ];
    }
}
