<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Tymon\JWTAuth\Http\Middleware\Authenticate as JWTMiddleware;
use Tymon\JWTAuth\Http\Middleware\RefreshToken as JWTRefresh;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register JWT middleware aliases
        $middleware->alias([
            'jwt.verify' => JWTMiddleware::class,
            'jwt.refresh' => JWTRefresh::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
