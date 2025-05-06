<?php

namespace App\Exceptions;

use App\Exceptions\Custom\BaseCustomException;
use App\Exceptions\Custom\EmailNotVerifiedException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            Log::error('Unhandled Exception', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        });

        $this->renderable(function (BaseCustomException $e, $request) {
            Log::warning('Custom Exception', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return $e->render($request);
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                Log::notice('Not Found Exception', [
                    'message' => $e->getMessage(),
                    'path' => $request->path()
                ]);
                return response()->json([
                    'success' => 0,
                    'message' => 'This route is not found',
                    'status' => '404',
                ], 404);
            }
        });

        $this->renderable(function (EmailNotVerifiedException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Please verify your email address',
                    'status' => '404',
                ], 403);
            }
        });
    }
}
