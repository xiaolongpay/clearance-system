<?php

use App\Http\Middleware\EnsurePasswordChanged;
use App\Http\Middleware\EnsureRegistrar;
use App\Http\Middleware\EnsureTechlab;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'techlab' => EnsureTechlab::class,
            'registrar' => EnsureRegistrar::class,
        ]);

        $middleware->appendToGroup('web', EnsurePasswordChanged::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (TokenMismatchException $e, $request) {
            return redirect()->route('login')->with('error', 'Your session expired. Please log in again.');
        });
    })->create();