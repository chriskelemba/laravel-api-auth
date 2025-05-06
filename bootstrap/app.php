<?php

use App\Exceptions\Custom\Database\ConnectionException;
use App\Exceptions\Custom\Database\DatabaseErrorException;
use App\Exceptions\Custom\ForbiddenException;
use App\Exceptions\Custom\ServerErrorException;
use App\Exceptions\Custom\UnauthenticatedException;
use App\Http\Middleware\UpdateLastUsedAt;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Exceptions\Custom\BaseCustomException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Psr\Log\LogLevel;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'last_used_at' => UpdateLastUsedAt::class,
            'password.reset.limit' => \App\Http\Middleware\PasswordResetRateLimiter::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontReportDuplicates();

        // Set log levels for specific exceptions
        $exceptions->level(PDOException::class, LogLevel::CRITICAL); // For database errors
        $exceptions->level(QueryException::class, LogLevel::ERROR); // For query errors
        $exceptions->level(ConnectionException::class, LogLevel::ALERT); // For connection errors

        $exceptions->level(AuthenticationException::class, LogLevel::NOTICE); // For unauthenticated errors
        $exceptions->level(ForbiddenException::class, LogLevel::ALERT); // For forbiden errors
        $exceptions->level(ServerErrorException::class, LogLevel::CRITICAL); // For server errors

        $exceptions->report(function (QueryException $e) {
        });

        $exceptions->report(function (ConnectionException $e) {
        });

        // Define when to render JSON responses
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }
            
            return $request->expectsJson();
        });
        
        // Register your custom exception handlers
        $exceptions->renderable(function (BaseCustomException $e, Request $request) {
            return $e->render($request);
        });
        
        // Handle Laravel's NotFoundHttpException
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => 0,
                    'message' => 'This route is not found',
                    'status' => '404',
                ], 404);
            }
        });
        
        // Handle other common exceptions
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => 0,   
                    'message' => 'Invalid token.Please login',
                    'status' => '401',
                ], 401);
            }
        });
        
        // General fallback for other exceptions
        $exceptions->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                
                return response()->json([
                    'success' => 0,
                    'message' => $e->getMessage() ?: 'Server Error',
                    'status' => (string) $statusCode,
                ], $statusCode);
            }
        });

    })->create();