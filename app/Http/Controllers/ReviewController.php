<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{


    public function store(Request $request, Product $product)
    {
            Log::info('ReviewController@store called');
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000'
        ]);
        $existing = Review::where('user_id', Auth::id())->where('product_id', $product['id'])->first();
        if ($existing) {
            return back()->with('error', 'You have already reviewed this product.');
        }
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product['id'],
            'rating' => $request['rating'],
            'comment' => $request['comment'],
        ]);
        return back()->with('success', 'Thank you for your review!');
    }

}
