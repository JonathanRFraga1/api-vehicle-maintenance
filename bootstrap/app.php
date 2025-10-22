<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // 401 Handler
        $exceptions->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Unauthenticated.',
                    'errors'  => null
                ], 401);
            }
        });

        // 403 Handler
        $exceptions->renderable(function (AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'This action is unauthorized.',
                    'errors'  => null
                ], 403);
            }
        });

        // 404 Handler
        $exceptions->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Resource not found.',
                    'errors'  => null
                ], 404);
            }
        });

        // 422 Handler
        $exceptions->renderable(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                    'errors'  => $e->errors()
                ], 422);
            }
        });

        // 500 Handler
        $exceptions->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                // Apenas se o debug estiver ativo
                if (!config('app.debug')) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Internal Server Error.',
                        'errors'  => null
                    ], 500);
                }
            }
        });

    })->create();
