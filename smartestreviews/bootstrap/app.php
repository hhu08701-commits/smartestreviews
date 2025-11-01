<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register custom middleware aliases
        $middleware->alias([
            'track.pageview' => \App\Http\Middleware\TrackPageView::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Redirect to admin login when accessing admin routes while unauthenticated
        $exceptions->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('admin/*') || $request->expectsJson()) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Unauthenticated.'], 401);
                }
                return redirect()->route('admin.login');
            }
            // For non-admin routes, redirect to login route (which redirects to admin.login)
            return redirect()->route('login');
        });
    })->create();
