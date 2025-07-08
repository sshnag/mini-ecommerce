<?php
  namespace App\Repositories;
  use App\Models\Product;
class ProductRepository{

    public function allPaginated($perPage=8){
        return Product::with('category')->latest()->paginate($perPage);
    }
    public function forUser($userid){
        return Product::where('user_id',$userid)->latest()->get();

    }
    public function search($query){
        return Product::where('name','like',"%$query%")->orWhere('description','like',"%query%")->get();

    }
    public function create(array $data){
        return Product::create($data);
    }
    public function update(Product $product,array $data){
        return $product->update($data);
    }
    public function getLatestJewels($limit=8){
       return Product::with(['category'])
            ->where('stock', '>', 0)
            ->latest('created_at')
            ->take($limit)
            ->get();
    }
}

