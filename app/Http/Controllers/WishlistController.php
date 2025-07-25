<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\View;

class WishlistController extends Controller
{
    // View wishlist
   public function index()
{
    if (Auth::check()) {
        $userId = Auth::id();
        $wishlistItems = Wishlist::with('product')->where('user_id', $userId)->get();
    } else {
        $sessionId = session()->getId();
        $wishlistItems = Wishlist::with('product')->where('session_id', $sessionId)->get();
    }
    return view('wishlist', compact('wishlistItems'));
}

    // Add to wishlist
  public function add(Request $request)
{
    try {
        $productId = $request->input('product_id');
        if (Auth::check()) {
            $userId = Auth::id();
            $sessionId = null;
        } else {
            $userId = null;
            $sessionId = session()->getId();
        }

        Log::info("Adding wishlist", ['user_id' => $userId, 'session_id' => $sessionId, 'product_id' => $productId]);

        $exists = Wishlist::where('product_id', $productId)
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();

        if ($exists) {
            return response()->json(['success' => 'Already in wishlist']);
        }

        Wishlist::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'product_id' => $productId,
        ]);

        return response()->json(['success' => 'Product added to wishlist']);
    } catch (Exception $e) {
        Log::error('Wishlist add error', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Something went wrong.'], 500);
    }
}

    // Remove from wishlist
    public function remove($id)
{
    if (Auth::check()) {
        $userId = Auth::id();
        $item = Wishlist::where('id', $id)->where('user_id', $userId)->first();
    } else {
        $sessionId = session()->getId();
        $item = Wishlist::where('id', $id)->where('session_id', $sessionId)->first();
    }

    if ($item) {
        $item->delete();
    }

    return redirect()->back()->with('success', 'Removed from wishlist.');
}

public function shareWishlistCount(){
    if(Auth::check()){
        $count= Wishlist::where('user_id',Auth::id())->count();
    }
    else{
        $count=Wishlist::where('session_id',session()->getId())->count();
    }
    View::share('wishlistCount',$count);
}
}
