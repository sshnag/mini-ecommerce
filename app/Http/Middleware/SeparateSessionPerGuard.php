<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SeparateSessionPerGuard
{
    public function handle(Request $request, Closure $next)
    {
        // Check which guard is authenticated and set appropriate session cookie
        if (Auth::guard('admin')->check()) {
            // Admin is logged in, use admin session cookie
            config(['session.cookie' => env('ADMIN_SESSION_COOKIE', 'admin_session')]);
            session(['admin_logged_in' => true]);
            session(['admin_user_id' => Auth::guard('admin')->id()]);
        } elseif (Auth::guard('web')->check()) {
            // Regular user is logged in, use user session cookie
            config(['session.cookie' => env('SESSION_COOKIE', 'laravel_session')]);
            session(['user_logged_in' => true]);
            session(['user_id' => Auth::guard('web')->id()]);
        } else {
            // No one is logged in, use default session
            config(['session.cookie' => env('SESSION_COOKIE', 'laravel_session')]);
        }
        
        return $next($request);
    }
}
