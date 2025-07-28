<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSessionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Set admin-specific session cookie name
        config(['session.cookie' => env('ADMIN_SESSION_COOKIE', 'admin_session')]);

        // Use a different session namespace for admin
        session(['admin_namespace' => true]);

        // Ensure admin session is separate from user session
        if (Auth::guard('admin')->check()) {
            // Admin is logged in, ensure we're using admin session
            session(['admin_logged_in' => true]);
            session(['admin_user_id' => Auth::guard('admin')->id()]);
        } else {
            // No admin logged in, clear any admin session data
            session()->forget(['admin_logged_in', 'admin_user_id']);
        }

        $response = $next($request);

        // Clear admin namespace after request
        session()->forget('admin_namespace');

        return $response;
    }
}
