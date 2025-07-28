<?php

namespace App\Listeners;

use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class TransferGuestCartToUserOnRegister
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
    public function handle(Registered $event): void
    {
        $user = $event->user;
        $guestSessionId = session('guest_session_id');
        $currentSessionId = Session::getId();

        Log::info('Registration listener triggered', [
            'user_id' => $user->id,
            'guest_session_id' => $guestSessionId,
            'current_session_id' => $currentSessionId,
            'is_authenticated' => Auth::check(),
            'all_session_data' => session()->all()
        ]);

        $sessionIdToUse = $guestSessionId ?: $currentSessionId;

        // Transfer cart items
        $guestCartItems = Cart::where('session_id', $sessionIdToUse)->get();
        Log::info('Found guest cart items (registration)', [
            'count' => $guestCartItems->count(),
            'session_id_used' => $sessionIdToUse,
            'cart_items' => $guestCartItems->toArray()
        ]);

        foreach ($guestCartItems as $cartItem) {
            $existingCartItem = Cart::where('user_id', $user->id)->where('product_id', $cartItem->product_id)->first();
            if ($existingCartItem) {
                $existingCartItem->quantity += $cartItem->quantity;
                $existingCartItem->save();
                $cartItem->delete();
                Log::info('Merged cart item (registration)', ['product_id' => $cartItem->product_id]);
            } else {
                $cartItem->user_id = $user->id;
                $cartItem->session_id = null;
                $cartItem->save();
                Log::info('Transferred cart item (registration)', ['product_id' => $cartItem->product_id]);
            }
        }

        // Transfer wishlist items
        $guestWishlistItems = Wishlist::where('session_id', $sessionIdToUse)->get();
        Log::info('Found guest wishlist items (registration)', [
            'count' => $guestWishlistItems->count(),
            'session_id_used' => $sessionIdToUse,
            'wishlist_items' => $guestWishlistItems->toArray()
        ]);

        foreach ($guestWishlistItems as $wishlistItem) {
            $existingWishlistItem = Wishlist::where('user_id', $user->id)->where('product_id', $wishlistItem->product_id)->first();
            if (!$existingWishlistItem) {
                $wishlistItem->user_id = $user->id;
                $wishlistItem->session_id = null;
                $wishlistItem->save();
                Log::info('Transferred wishlist item (registration)', ['product_id' => $wishlistItem->product_id]);
            } else {
                $wishlistItem->delete();
                Log::info('Deleted duplicate wishlist item (registration)', ['product_id' => $wishlistItem->product_id]);
            }
        }
        session()->forget('guest_session_id');

        Log::info('Registration transfer process completed');
    }
}
