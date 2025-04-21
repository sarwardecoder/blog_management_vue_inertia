<?php
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware (if needed)
        // $middleware->append(\App\Http\Middleware\SomeGlobalMiddleware::class);
        //Inertia JS
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        // Register route middleware aliases
        $middleware->alias([
            'AuthCheck' => \App\Http\Middleware\AuthCheck::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
