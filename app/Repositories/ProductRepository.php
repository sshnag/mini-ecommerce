<?php
  namespace App\Repositories;
  use App\Models\Product;
class ProductRepository{

    public function getAllWithPagination(int $perpage=5){
        return Product::with(['categories','user'])->latest()->paginate($perpage);
    }
    public function getSupplierProducts(int $supplierId){
            return Product::where('user_id',$supplierId)->with('categories')->latest()->get();
    }
}

