<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenAbsentException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'jwt.verify' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (TokenExpiredException $e) {
            return response()->json(['message' => 'Token has expired'], 401);
        });

        $exceptions->renderable(function (TokenInvalidException $e) {
            return response()->json(['message' => 'Token is invalid'], 401);
        });

        $exceptions->renderable(function (TokenAbsentException $e) {
            return response()->json(['message' => 'Token not provided'], 401);
        });

        $exceptions->renderable(function (UnauthorizedHttpException $e) {
            return response()->json(['message' => $e->getMessage() ?: 'Unauthorized'], 401);
        });
    })->create();
