<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Product;
use App\Policies\ProductPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('superadmin',function($user){
            return $user->role ==='superadmin';
        });
        Gate::define('access-admin-panel', function ($user) {
        return in_array($user->role, ['admin', 'superadmin']);
    });
        // Example Gate for Superadmin
        Gate::define('isSuperadmin', fn($user) => $user->hasRole('superadmin'));
    }
}
