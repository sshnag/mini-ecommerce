<?php
namespace App\Services;

use App\Models\Product;

class ProductServcie{
    public function createProduct(array $data):Product{
return Product::create($data+[
    'custom_id'=>$this->generateProductId()
]);
    }
    private function generateProductId(){
        $lastId=Product::max('id') ?? 0;
        return 'PROD-'.str_pad($lastId+1,6,'0',STR_PAD_LEFT);
    }
}
