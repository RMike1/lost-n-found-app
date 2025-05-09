<?php

use App\Enums\Api\ApiException;
use App\Exceptions\AppException;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Auth\Access\AuthorizationException;
// use Illuminate\Auth\Access\AuthenticationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AppException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'status' => $e->getCode() ?: 500,
                ], $e->getCode() ?: 500);
            }
        });
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                $exception = match (true) {
                    $e instanceof ValidationException => ApiException::VALIDATION,
                    $e instanceof ModelNotFoundException => ApiException::NOT_FOUND,
                    $e instanceof AuthorizationException => ApiException::FORBIDDEN,
                    $e instanceof AuthenticationException => ApiException::AUTH_FAILED,
                    $e instanceof ThrottleRequestsException => ApiException::THROTTLE,
                    default => ApiException::SERVER_ERROR
                };

                $response = $exception->response();

                if ($e instanceof ValidationException) {
                    $response['errors'] = $e->errors();
                } else {
                    $response['message'] = $e->getMessage() ?: $response['message'];
                }

                return response()->json($response, $response['status']);
            }
        });
    })->create();
