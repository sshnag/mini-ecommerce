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
    $userId = Auth::check() ? Auth::id() : session()->getId();
    $wishlistItems = Wishlist::with('product')->where('user_id', $userId)->get();

    return view('wishlist', compact('wishlistItems'));
}

    // Add to wishlist
  public function add(Request $request)
{
    try {
        $productId = $request->input('prod_id'); // FIXED
        $userId = Auth::check() ? Auth::id() : session()->getId();

        Log::info("Adding wishlist", ['user_id' => $userId, 'prod_id' => $productId]);

        $exists = Wishlist::where('user_id', $userId)
                          ->where('prod_id', $productId)
                          ->first();

        if ($exists) {
            return response()->json(['success' => 'Already in wishlist']);
        }

        Wishlist::create([
            'user_id' => $userId,
            'prod_id' => $productId, // FIXED
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
    $userId = Auth::check() ? Auth::id() : session()->getId();

    $item = Wishlist::where('id', $id)->where('user_id', $userId)->first();

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
        $count=Wishlist::where('user_id',session()->getId())->count();
    }
    View::share('wishlistCount',$count);
}
}
