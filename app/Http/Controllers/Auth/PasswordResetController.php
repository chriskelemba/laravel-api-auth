<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\Auth\ForgotPasswordService;
use App\Services\Auth\ResetPasswordService;

class PasswordResetController extends Controller
{
    protected $forgotPasswordService;
    protected $resetPasswordService;

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
        return response()->json($response, $response['status']);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $response = $this->resetPasswordService->execute(
            $request->token,
            $request->password
        );
        return response()->json($response, $response['status']);
    }
}