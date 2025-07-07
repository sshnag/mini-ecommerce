<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Catgory;

class Product extends Model
{
    use SoftDeletes,HasFactory;

    protected $casts=[
        'available_sizes'=>'array'
    ];
    public function getSizes(){
        return $this->available_sizes ?? $this->category->default_sizes;
    }
    public function requiredSizes(){
        return $this->category->size_type != 'none';
    }
    public function categories(){
        return $this->belongsTo(Category::class);
    }
    public function suppliers(){
        return $this->belongsTo(Supplier::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $lastId = Product::withTrashed()->max('id') ?? 0;
            $product->custom_id = 'PROD-' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
        });
    }
}
