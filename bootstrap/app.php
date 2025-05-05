<?php

use App\Exceptions\Database\QueryException;
use App\Http\Middleware\UpdateLastUsedAt;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Exceptions\Custom\BaseCustomException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'last_used_at' => UpdateLastUsedAt::class,
            'password.reset.limit' => \App\Http\Middleware\PasswordResetRateLimiter::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Define JSON response rendering
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });
        
        // Custom exceptions
        $exceptions->renderable(function (BaseCustomException $e, Request $request) {
            return $e->render($request);
        });
        
        // Database connection exceptions
        $exceptions->renderable(function (\App\Exceptions\Database\DatabaseConnectionException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'Service temporarily unavailable',
                'status' => 503
            ], 503);
        });
        
        // Query exceptions (SQL errors)
        $exceptions->renderable(function (QueryException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred',
                'status' => 500
            ], 500);
        });
        
        // PDO exceptions (connection issues)
        $exceptions->renderable(function (PDOException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'Database service unavailable',
                'status' => 503
            ], 503);
        });
        
        // Not found exceptions
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
                'status' => 404
            ], 404);
        });
        
        // Authentication exceptions
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'status' => 401
            ], 401);
        });
        
        // Fallback for all other exceptions
        $exceptions->renderable(function (Throwable $e, Request $request) {
            $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
            
            $message = config('app.debug') 
                ? $e->getMessage() 
                : 'An error occurred';
            
            return response()->json([
                'success' => false,
                'message' => $message,
                'status' => $statusCode
            ], $statusCode);
        });
        
        // Report database exceptions with context
        $exceptions->reportable(function (QueryException $e) {
            \Log::error('Database query error', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'trace' => $e->getTraceAsString()
            ]);
        });
        
        $exceptions->reportable(function (PDOException $e) {
            \Log::critical('Database connection failed', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString()
            ]);
        });
    })
    ->create();