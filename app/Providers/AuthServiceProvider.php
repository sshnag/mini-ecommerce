<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Policies\ProductPolicy;
use App\Models\User;
use App\Models\Category;
use App\Policies\CategoryPolicy;


class AuthServiceProvider extends ServiceProvider
{
       protected $policies = [
        Product::class => ProductPolicy::class,
        Category::class => CategoryPolicy::class,
];


    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('superadmin', function ($user) {
    return $user->hasRole('superadmin');
});
        // Global gatesr
        Gate::define('access-admin-panel', fn(User $user) => $user->hasRole('admin') || $user->hasRole('superadmin'));
        Gate::define('delete-product', fn(User $user) => $user->hasRole('superadmin'));
        Gate::define('delete-category', fn(User $user) => $user->hasRole('superadmin'));
        Gate::define('delete-user', fn(User $user) => $user->hasRole('superadmin'));
        Gate::define('delete-order', fn(User $user) => $user->hasRole('superadmin'));

        // Optional alias
        Gate::define('isSuperadmin', fn(User $user) => $user->hasRole('superadmin'));
    }
}
