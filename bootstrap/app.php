<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // Add this import
use App\Exceptions\Custom\BaseCustomException; // Import your base exception class
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Define when to render JSON responses
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true; // Always render JSON for 'api/*' routes
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
                    'message' => 'Unauthenticated',
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