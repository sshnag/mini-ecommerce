<?php

use App\Jobs\DeleteOrders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',


        )
        ->withSchedule(function(Schedule $schedule){
            $schedule->job(new DeleteOrders)->cron('0 0 1  */6 *');
        })
    ->withMiddleware(function (Middleware $middleware): void {
        //
           $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'check.superadmin' => \App\Http\Middleware\CheckSuperadmin::class,
            'admin.session' => \App\Http\Middleware\AdminSessionMiddleware::class,


    ]);


    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
