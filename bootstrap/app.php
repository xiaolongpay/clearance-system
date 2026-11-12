<?php

use App\Http\Controllers\SignatureImageController;
use App\Http\Middleware\EnsurePasswordChanged;
use App\Http\Middleware\EnsureRegistrar;
use App\Http\Middleware\EnsureTechlab;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // The signature images are kept in the database, because the hosting service has no permanent disk.
            // This address is the one the pages already use (storage/signatures/{office number}).
            Route::get('/storage/signatures/{id}', [SignatureImageController::class, 'show'])
                ->whereNumber('id')
                ->name('signature.image');
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // The hosting service sits in front of the application, so its forwarded headers are trusted.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'techlab' => EnsureTechlab::class,
            'registrar' => EnsureRegistrar::class,
        ]);

        $middleware->appendToGroup('web', EnsurePasswordChanged::class);

        // Logging out must always work, even from a page that has been open for a long time
        // or from an old tab. An expired page then simply ends up at the login page.
        $middleware->validateCsrfTokens(except: ['logout']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (TokenMismatchException $e, $request) {
            return redirect()->route('login')->with('error', 'Your session expired. Please log in again.');
        });
    })->create();
