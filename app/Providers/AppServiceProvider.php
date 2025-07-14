<?php

namespace App\Providers;
use App\Services\CartService;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

use Illuminate\Pagination\Paginator;
use App\Models\Cart;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
   public function register()
{
    $this->app->singleton(CartService::class, function ($app) {
        return new CartService();
    });
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    View::composer('*', function ($view) {
        $cartCount = 0;

        if (Auth::check()) {
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        }

        $view->with('cartCount', $cartCount);
    });
        //
    Paginator::useBootstrapFive();

    }
}
