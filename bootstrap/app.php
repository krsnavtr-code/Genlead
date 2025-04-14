<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware (if any) can be added here
        // Define middleware aliases
        $middleware->alias([
            'checkrole' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Append StartSession middleware
        $middleware->append(StartSession::class);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
