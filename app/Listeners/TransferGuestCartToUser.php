<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Wishlist;

class TransferGuestCartToUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        $guestSessionId = session('guest_session_id');
        $currentSessionId = Session::getId();

        // Debug: Log the session IDs and user info
        Log::info('Login listener triggered', [
            'user_id' => $user->id,
            'guest_session_id' => $guestSessionId,
            'current_session_id' => $currentSessionId,
            'is_authenticated' => Auth::check(),
            'all_session_data' => session()->all()
        ]);

        // Use guest session ID if available, otherwise use current session ID
        $sessionIdToUse = $guestSessionId ?: $currentSessionId;

        // Transfer cart items
        $guestCartItems = Cart::where('session_id', $sessionIdToUse)->get();

        Log::info('Found guest cart items', [
            'count' => $guestCartItems->count(),
            'session_id_used' => $sessionIdToUse,
            'cart_items' => $guestCartItems->toArray()
        ]);

        foreach ($guestCartItems as $cartItem) {
            $existingCartItem = Cart::where('user_id', $user->id)
                                  ->where('product_id', $cartItem->product_id)
                                  ->first();

            if ($existingCartItem) {
                // Update quantity
                $existingCartItem->quantity += $cartItem->quantity;
                $existingCartItem->save();
                // Delete the guest cart item
                $cartItem->delete();
                Log::info('Merged cart item', ['product_id' => $cartItem->product_id]);
            } else {
                // Transfer the cart item to user
                $cartItem->user_id = $user->id;
                $cartItem->session_id = null;
                $cartItem->save();
                Log::info('Transferred cart item', ['product_id' => $cartItem->product_id]);
            }
        }

        // Transfer wishlist items
        $guestWishlistItems = Wishlist::where('session_id', $sessionIdToUse)->get();

        Log::info('Found guest wishlist items', [
            'count' => $guestWishlistItems->count(),
            'session_id_used' => $sessionIdToUse,
            'wishlist_items' => $guestWishlistItems->toArray()
        ]);

        foreach ($guestWishlistItems as $wishlistItem) {
            // Check if user already has this product in wishlist
            $existingWishlistItem = Wishlist::where('user_id', $user->id)
                                          ->where('product_id', $wishlistItem->product_id)
                                          ->first();

            if (!$existingWishlistItem) {
                // Transfer the wishlist item to user
                $wishlistItem->user_id = $user->id;
                $wishlistItem->session_id = null;
                $wishlistItem->save();
                Log::info('Transferred wishlist item', ['product_id' => $wishlistItem->product_id]);
            } else {
                // Delete the duplicate guest wishlist item
                $wishlistItem->delete();
                Log::info('Deleted duplicate wishlist item', ['product_id' => $wishlistItem->product_id]);
            }
        }

        // Clear the stored guest session ID
        session()->forget('guest_session_id');
        
        Log::info('Transfer process completed');
    }
}
