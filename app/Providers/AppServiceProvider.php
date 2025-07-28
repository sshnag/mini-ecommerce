<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Models\Cart;
use App\Models\Wishlist;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CartService::class, function ($app) {
            return new CartService();
        });
    }

    public function boot(): void
    {


        // Use Bootstrap 5 pagination
        Paginator::useBootstrapFive();

        //Share wishlist and cart counts with all views
        View::composer('*',function($view){
            $wishlistCount=0;
            $cartCount=0;
            if (Auth::check()) {
                $wishlistCount=Wishlist::where('user_id',Auth::id())->count();
                $cartCount=Cart::where('user_id',Auth::id())->count();
            }
            else {
                $sessionId=session()->getId();
                $wishlistCount=Wishlist::where('session_id',$sessionId)->count();
                $cartCount=Cart::where('session_id', $sessionId)->count();
            }
            $view->with(['wishlistCount'=>$wishlistCount, 'cartCount'=>$cartCount]);
        });

}
}
