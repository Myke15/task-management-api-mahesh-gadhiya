<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //Customizing Form validation response
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'result'    => false,
                    'message'   => $e->getMessage(),
                    'all_failed_validations'   => collect($e->errors())->flatten(),
                ], 422);
            }
        });

        // Customizing the 429 too many attemps
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'result'    => false,
                    'message'   => $e->getMessage()
                ], 429);
            }
        });

        // Customizing the Unauthenticated response
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'result'    => false,
                    'message'   => $e->getMessage()
                ], 401);
            }
        });

        // Customizing the 404 Not found exception
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'result'    => false,
                    'message'   => "Record not found."
                ], 404);
            }
        });

        // Customizing the 403 unauthorized or forbidden exception
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'result'    => false,
                    'message'   => $e->getMessage()
                ], 403);
            }
        });
    })->create();
