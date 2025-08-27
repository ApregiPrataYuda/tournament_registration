<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\App;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use App\Helpers\ApiResponse;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Routing\Middleware\SubstituteBindings;
use App\Http\Middleware\FormatUnauthenticated;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
     //untuk pengelolaan api yang bersipat SPA
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(append: [
        EnsureFrontendRequestsAreStateful::class,
        SubstituteBindings::class,
        'throttle:api',
        FormatUnauthenticated::class,
        \Illuminate\Http\Middleware\HandleCors::class,
    ]);
    })
    
    
    ->withExceptions(function (Exceptions $exceptions) {
        // Validation errors (422)
        $exceptions->renderable(function (ValidationException $e, $request): JsonResponse {
            return ApiResponse::error(
                'Validation failed',
                $e->errors(),
                $e->status
            );
        });

        // Authentication / Unauthorized (401)
        $exceptions->renderable(function (AuthenticationException $e, $request): JsonResponse {
            return ApiResponse::error(
                'Kamu harus login dan butuh token.',
                null,
                401
            );
        });

        // Not Found (404)
        $exceptions->renderable(function (NotFoundHttpException $e, $request): JsonResponse {
            return ApiResponse::error(
                'Resource tidak ditemukan.',
                null,
                404
            );
        });

        // Database connection error (PDO)
        // $exceptions->render(function (\PDOException $e, $request): JsonResponse {
        //     return ApiResponse::error(
        //         'Tidak bisa terhubung ke database.',
        //         null,
        //         500
        //     );
        // });

        $exceptions->render(function (\PDOException $e, $request): JsonResponse {
            if (App::isProduction() && !App::isLocal()) {
                // Production, tampilkan pesan umum
                return ApiResponse::error(
                    'Tidak bisa terhubung ke database.',
                    null,
                    500
                );
            } else {
                // Development/local, tampilkan detail error untuk debugging
                return ApiResponse::error(
                    $e->getMessage(),
                    null,
                    500
                );
            }
        });

        // Query error (misalnya syntax SQL salah)
        $exceptions->render(function (QueryException $e, $request): JsonResponse {
            return ApiResponse::error(
                'Terjadi kesalahan saat menjalankan query.',
                null,
                500
            );
        });

       
    })

    ->create();