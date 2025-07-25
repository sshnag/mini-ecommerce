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
        // Share cart count with all views
        View::composer('*', function ($view) {
            $cartCount = 0;

            if (Auth::check()) {
                $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
            }

            $view->with('cartCount', $cartCount);
        });

        // Use Bootstrap 5 pagination
        Paginator::useBootstrapFive();

        // Share wishlist count with all views
        View::composer('*', function ($view) {
            $wishlistCount = 0;

            if (Auth::check()) {
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
            } else {
                $wishlistCount = Wishlist::where('session_id', session()->getId())->count();
            }

            $view->with('wishlistCount', $wishlistCount);
        });
    }
}
