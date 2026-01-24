<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'verified.identity' => \App\Http\Middleware\EnsureUserIsVerified::class,
            'has.profile' => \App\Http\Middleware\EnsureUserHasProfile::class,
            'auth.api' => \App\Http\Middleware\AuthenticateApi::class,
        ]);

        // Agregar middleware global para actualizar última actividad
        $middleware->web(append: [
            \App\Http\Middleware\UpdateLastActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
