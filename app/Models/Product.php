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

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'custom_id',
        'user_id',
        'category_id',
        'supplier_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'available_sizes'
    ];
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
                        $product->id = (string) Str::uuid();
            $nextNumber=Product::withTrashed()->count()+1;
            $product->custom_id = 'PROD-' . str_pad($nextNumber , 6, '0', STR_PAD_LEFT);
        });
    }
}
