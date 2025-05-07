<?php

namespace App\Http\Controllers;

use App\Class\ApiResponseClass;
use App\Exceptions\Custom\EmailAlreadyVerifiedException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\AuthResponseResource;
use App\Services\Auth\AuthService;
use App\Services\Auth\EmailVerificationService;
use App\Services\Auth\ResendVerificationEmailService;

class AuthController extends Controller
{
    private $authService;

    protected $emailVerificationService;

    public function __construct(
        AuthService $authService,
        ResendVerificationEmailService $emailVerificationService
    ) {
        $this->authService = $authService;
        $this->emailVerificationService = $emailVerificationService;
    }

    // login user
    public function login(LoginRequest $request)
    {
        $response = $this->authService->login($request->only(['email', 'password']));

        return ApiResponseClass::sendResponse(
            new AuthResponseResource($response['user'], $response['token']),
            'Login Successful',
            200
        );
    }


    // register user
    public function register(RegisterRequest $request)
    {
        $response = $this->authService->register($request->only(['name', 'email', 'password']));
        return ApiResponseClass::sendResponse(['user' => new AuthResource($response['user']), 'token' => $response['token'],], 'User registered successfully', 200);
    }

    // logout user
    public function logout()
    {
        $this->authService->logout();
        return ApiResponseClass::sendResponse('Logout Successful', '', 200);
    }

    public function resendVerificationEmail()
    {
        $user = auth()->user();
        $response = $this->emailVerificationService->execute($user);
        return ApiResponseClass::sendResponse(null, $response['message'], $response['status']);
    }
}
