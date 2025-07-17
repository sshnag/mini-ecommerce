<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class AdminSessionMiddleware
{
    public function handle($request, Closure $next)
    {
        Config::set('session.cookie', 'admin_session');
        return $next($request);
    }
}
