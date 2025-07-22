<?php

namespace App\Services;

/**
 * Summary of ReviewService
 * Calculate the average rating
 */
class ReviewService
{
    public function avaerageRating($product){
        return $product->review()->avg('rating') ??0;
    }
}
