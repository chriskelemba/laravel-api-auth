<?php

namespace App\Http\Controllers\Auth;

use App\Class\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\Auth\ForgotPasswordService;
use App\Services\Auth\ResetPasswordService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PasswordResetController extends Controller implements HasMiddleware
{
    protected $forgotPasswordService;
    protected $resetPasswordService;

    public static function middleware(): array
    {
        return [
            new Middleware('password.reset.limit', only: ['forgotPassword', 'resetPassword']),
        ];
    }
    public function __construct(
        ForgotPasswordService $forgotPasswordService,
        ResetPasswordService $resetPasswordService
    ) {
        $this->forgotPasswordService = $forgotPasswordService;
        $this->resetPasswordService = $resetPasswordService;
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $response = $this->forgotPasswordService->execute($request->email);

        return ApiResponseClass::sendResponse($response['message'], $response['data'] ?? [], $response['status']);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $response = $this->resetPasswordService->execute(
            $request->token,
            $request->password
        );

        return ApiResponseClass::sendResponse($response['message'], $response['data'] ?? [], $response['status']);
    }

    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token
        ]);
    }
}