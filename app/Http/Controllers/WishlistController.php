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
        
        Log::info('Adding to wishlist', [
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'session_id' => session()->getId(),
            'is_authenticated' => Auth::check()
        ]);

        if (Auth::check()) {
            $userId = Auth::id();
            $sessionId = null;
        } else {
            $userId = null;
            $sessionId = session()->getId();
        }

        $exists = Wishlist::where('product_id', $productId)
            ->when($userId, function($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($sessionId, function($query) use ($sessionId) {
                return $query->where('session_id', $sessionId);
            })
            ->first();

        Log::info('Checking if item exists', [
            'exists' => $exists ? $exists->id : null,
            'user_id' => $userId,
            'session_id' => $sessionId
        ]);

        if ($exists) {
            $count = Wishlist::when($userId, function($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })
                ->when($sessionId, function($query) use ($sessionId) {
                    return $query->where('session_id', $sessionId);
                })
                ->count();

            Log::info('Item already exists in wishlist', [
                'wishlist_id' => $exists->id,
                'count' => $count
            ]);

            return response()->json([
                'success' => 'Already in wishlist',
                'count' => $count,
                'wishlist_id' => $exists->id
            ]);
        }

        $wishlist = Wishlist::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'product_id' => $productId,
        ]);

        Log::info('Created new wishlist item', [
            'wishlist_id' => $wishlist->id,
            'product_id' => $productId
        ]);

        $count = Wishlist::when($userId, function($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($sessionId, function($query) use ($sessionId) {
                return $query->where('session_id', $sessionId);
            })
            ->count();

        return response()->json([
            'success' => 'Product added to wishlist',
            'count' => $count,
            'wishlist_id' => $wishlist->id
        ]);
    } catch (Exception $e) {
        Log::error('Wishlist add error', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Something went wrong.'], 500);
    }
}

    // Remove from wishlist
  public function remove($id)
{
    Log::info('Wishlist remove method called', ['id' => $id, 'request_method' => request()->method()]);
    Log::info('Request details', [
        'is_ajax' => request()->ajax(),
        'headers' => request()->headers->all(),
        'content_type' => request()->header('Content-Type'),
        'accept' => request()->header('Accept'),
        'csrf_token' => request()->header('X-CSRF-TOKEN'),
        'url' => request()->url(),
        'method' => request()->method()
    ]);
    
    try {
        $userId = null;
        $sessionId = null;

        if (Auth::check()) {
            $userId = Auth::id();
            $item = Wishlist::where('id', $id)
                      ->where('user_id', $userId)
                      ->first();
        } else {
            $sessionId = session()->getId();
            $item = Wishlist::where('id', $id)
                      ->where('session_id', $sessionId)
                      ->first();
        }

        // If item doesn't exist, return success anyway (it was already deleted)
        if (!$item) {
            Log::info('Wishlist item not found - likely already deleted', ['id' => $id, 'user_id' => $userId, 'session_id' => $sessionId]);
            
            // Get current count
            $count = 0;
            try {
                if (Auth::check()) {
                    $count = Wishlist::where('user_id', Auth::id())->count();
                } else {
                    $count = Wishlist::where('session_id', session()->getId())->count();
                }
            } catch (Exception $countError) {
                Log::error('Count error: ' . $countError->getMessage());
            }
            
            return response()->json([
                'success' => 'Item already removed from wishlist',
                'count' => $count,
                'wishlist_id' => null
            ]);
        }

        // Log before deletion
        Log::info('Deleting wishlist item', ['id' => $id, 'item_id' => $item->id]);
        
        $item->delete();

        // Get updated wishlist count
        $count = 0;
        try {
            if (Auth::check()) {
                $count = Wishlist::where('user_id', Auth::id())->count();
            } else {
                $count = Wishlist::where('session_id', session()->getId())->count();
            }
            Log::info('Wishlist count after deletion', ['count' => $count]);
        } catch (Exception $countError) {
            Log::error('Wishlist count error after delete: ' . $countError->getMessage());
            $count = 0;
        }

        // Always return success for AJAX requests
        $response = [
            'success' => 'Removed from wishlist.',
            'count' => $count,
            'wishlist_id' => null
        ];
        Log::info('Sending response', $response);
        return response()->json($response);

    } catch (Exception $e) {
        Log::error('Wishlist remove error: ' . $e->getMessage(), [
            'id' => $id,
            'user_id' => Auth::id(),
            'session_id' => session()->getId(),
            'trace' => $e->getTraceAsString()
        ]);
        
        // Even if there's an error, try to get the current count
        $count = 0;
        try {
            if (Auth::check()) {
                $count = Wishlist::where('user_id', Auth::id())->count();
            } else {
                $count = Wishlist::where('session_id', session()->getId())->count();
            }
        } catch (Exception $countError) {
            Log::error('Count error in exception handler: ' . $countError->getMessage());
        }
        
        return response()->json([
            'error' => 'Failed to remove from wishlist',
            'count' => $count
        ], 500);
    }
}
public function shareWishlistCount(){
    try {
        if(Auth::check()){
            $count = Wishlist::where('user_id', Auth::id())->count();
        } else {
            $count = Wishlist::where('session_id', session()->getId())->count();
        }
        View::share('wishlistCount', $count);
    } catch (Exception $e) {
        Log::error('Wishlist count error: ' . $e->getMessage());
        View::share('wishlistCount', 0);
    }
}

    // Find wishlist item by product ID
    public function find($productId)
    {
        try {
            if (Auth::check()) {
                $wishlistItem = Wishlist::where('product_id', $productId)
                    ->where('user_id', Auth::id())
                    ->first();
            } else {
                $wishlistItem = Wishlist::where('product_id', $productId)
                    ->where('session_id', session()->getId())
                    ->first();
            }

            if ($wishlistItem) {
                return response()->json([
                    'wishlist_id' => $wishlistItem->id,
                    'found' => true
                ]);
            } else {
                return response()->json([
                    'wishlist_id' => null,
                    'found' => false
                ]);
            }
        } catch (Exception $e) {
            Log::error('Wishlist find error: ' . $e->getMessage());
            return response()->json([
                'wishlist_id' => null,
                'found' => false,
                'error' => 'Failed to find wishlist item'
            ], 500);
        }
    }
}
