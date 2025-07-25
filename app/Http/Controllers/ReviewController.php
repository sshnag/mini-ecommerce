<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    /**
     * Summary of store
     * Storing data from reviews' data from users
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Product $product)
    {
            Log::info('ReviewController@store called');
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000'
        ]);
        //if the user reviewing the same product
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

    public function edit(Review $review){
        //authorization check
        if (Gate::denies('manage-reviews',$review)) {
            abort(403, 'Unauthorized action');

        }
    }
    public function update(Request $request,Review $review){
        //Authorization check
        if ($review->user_id !== Auth::id()) {
            abort(403,'Unauthorized access');
        }
        $request->validate([
            'rating'=>'required|integer|min:1|max:5',
            'comment'=>'nullable|string|max:2000'
        ]);
        $review->update([
            'rating'=>$request->rating,
            'comment'=>$request->comment,
        ]);
        return response()->json([
            'success'=>true,
            'review'=>[
                'rating'=>$review->rating,
                'comment'=> $review->comment,
                'updated_at'=>$review->updated_at->diffForHumans()
            ]
            ]);
    }
    public function destroy(Review $review){
        //Authorization check
        if ($review->user_id !== Auth::id()) {
            abort(403,'Unauthorized action');

        }
        $review->delete();
        return response()->json(['success'=>true]);
    }
}
