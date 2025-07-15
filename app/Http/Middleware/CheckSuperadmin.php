<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSuperadmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('admin')->user();

        // Check if user is authenticated as admin
        if (!$user) {
            logger()->warning('Admin authentication failed', [
                'ip' => $request->ip(),
                'route' => $request->route()?->getName()
            ]);
            return redirect()->route('admin.login');
        }

        // PROPER role check using Spatie's methods
        if (!$user->hasRole('superadmin')) {
            logger()->warning('Role verification failed', [
                'user_id' => $user->id,
                'roles' => $user->getRoleNames()->toArray()
            ]);
            abort(403, 'Insufficient privileges');
        }

        return $next($request);
    }
}
