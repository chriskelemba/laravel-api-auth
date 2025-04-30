<?php

namespace App\Exceptions;

use App\Exceptions\Custom\BaseCustomException;
use App\Exceptions\Custom\EmailNotVerifiedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle all custom exceptions derived from BaseCustomException
        $this->renderable(function (BaseCustomException $e, $request) {
            return $e->render($request);
        });
        
        // Handle Laravel's NotFoundHttpException
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
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
