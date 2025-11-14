<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsEditorOrAdmin;
use App\Http\Middleware\SetLocale;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: __DIR__.'/../routes/health.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => Authenticate::class,
            'verified' => EnsureEmailIsVerified::class,
            'role' => EnsureUserHasRole::class,
            'admin' => EnsureUserIsAdmin::class,
            'editorOrAdmin' => EnsureUserIsEditorOrAdmin::class,
        ]);

        $middleware->web(
            append: [
                SetLocale::class,
            ],
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Customize exception handling if necessary.
    })
    ->create();
