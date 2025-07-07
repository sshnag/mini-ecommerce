<?php

namespace App\Services;

class ReviewService
{
    public function avaerageRating($product){
        return $product->review()->avg('rating') ??0;
    }
}
