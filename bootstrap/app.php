<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api_v1.php'));

            Route::middleware('web')
                ->prefix('web')
                ->group(base_path('routes/web.php'));
        },
        commands: __DIR__.'/../routes/console.php',
    )
//    ->withRouting(
//        web: __DIR__.'/../routes/web.php',
//        api: __DIR__.'/../routes/api.php',
//        commands: __DIR__.'/../routes/console.php',
//        health: '/up',
//        then: function () {
//            Route::middleware('api')
//                ->prefix('api/v1')
//                ->group(base_path('routes/api_v1.php'));
//        },
//
//    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $e) {
            foreach ($e->errors() as $key => $value) {
                foreach ($value as $message) {
                    $errors[] = [
                        'status' => $e->status,
                        'message' => $message,
                        'source' => $key,
                    ];
                }
            }

            return response()->json([
                'errors' => $errors,
            ]);
        });

        $exceptions->render(function (NotFoundHttpException $e) {

            $errors[] = [
                'status' => 404,
                'message' => $e->getMessage(),
                'source' => ''
            ];

            return response()->json([
                'errors' => $errors,
            ]);
        });

        $exceptions->render(function (AccessDeniedHttpException $e) {
            $errors[] = [
                'status' => $e->getStatusCode(),
                'message' => $e->getMessage(),
                'source' => '',
            ];

            return response()->json([
                'errors' => $errors,
            ]);
        });

        $exceptions->render(function (AuthenticationException $e) {
            $errors[] = [
                'status' => 401,
                'message' => $e->getMessage(),
                'source' => '',
            ];

            return response()->json([
                'errors' => $errors,
            ]);
        });
    })->create();
