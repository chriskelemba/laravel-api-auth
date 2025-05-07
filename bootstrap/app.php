<?php

// use PDOException;
use App\Exceptions\Custom\UserAuthenticationException;
use App\Exceptions\Custom\UserAuthorizationException;
use App\Http\Middleware\EnsureUserIsNotBlocked;
use Psr\Log\LogLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Application;
use Illuminate\Database\QueryException;
use App\Http\Middleware\UpdateLastUsedAt;
use Illuminate\Auth\AuthenticationException;
use App\Exceptions\Custom\ForbiddenException;
use App\Exceptions\Custom\BaseCustomException;
use App\Exceptions\Custom\ServerErrorException;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Exceptions\Custom\DatabaseQueryException;
use App\Exceptions\Custom\DatabaseErrorException;
use App\Http\Middleware\PasswordResetRateLimiter;
use App\Exceptions\Custom\UnauthenticatedException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Exceptions\Custom\DatabaseConnectionException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use App\Exceptions\Custom\Database\ConnectionException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withEvents([
        __DIR__ . '/../app/Listeners'
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'last_used_at' => UpdateLastUsedAt::class,
            'password.reset.limit' => PasswordResetRateLimiter::class,
            'not_blocked' => EnsureUserIsNotBlocked::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontReportDuplicates();

        $exceptions->render(function (QueryException $e, $request) {
            $message = $e->getMessage();

            if (DatabaseConnectionException::isConnectionError($message)) {
                throw new DatabaseConnectionException();
            } elseif (DatabaseQueryException::isQueryError($message)) {
                throw new DatabaseQueryException();
            } else {
                // All other database errors
                throw new DatabaseErrorException();
            }
        });

        // Set log levels for specific exceptions
        $exceptions->level(PDOException::class, LogLevel::CRITICAL); // For database errors
        // $exceptions->level(QueryException::class, LogLevel::ERROR); // For query errors
        // $exceptions->level(ConnectionException::class, LogLevel::ALERT); // For connection errors
    
        // $exceptions->level(AuthenticationException::class, LogLevel::NOTICE); // For unauthenticated errors
        $exceptions->level(UserAuthenticationException::class, LogLevel::NOTICE); // For unauthenticated errors
        $exceptions->level(UserAuthorizationException::class, LogLevel::WARNING); // For unauthenticated errors
        $exceptions->level(ForbiddenException::class, LogLevel::ALERT); // For forbiden errors
        $exceptions->level(ServerErrorException::class, LogLevel::CRITICAL); // For server errors
    
        // $exceptions->report(function (QueryException $e) {
        // });
    
        // $exceptions->report(function (ConnectionException $e) {
        // });
    
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