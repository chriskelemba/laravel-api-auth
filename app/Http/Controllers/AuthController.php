<?php

namespace App\Http\Controllers;

use App\Class\ApiResponseClass;
use App\Exceptions\Custom\EmailAlreadyVerifiedException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Services\Auth\LoginService;
use App\Services\Auth\LogoutService;
use App\Services\Auth\RegisterService;
use App\Services\Auth\ResendVerificationEmailService;

class AuthController extends Controller
{
    private $loginService;
    private $registerService;
    private $logoutService;

    protected $resendVerificationEmailService;

    public function __construct(
        LoginService $loginService,
        RegisterService $registerService,
        LogoutService $logoutService,
        ResendVerificationEmailService $resendVerificationEmailService
    ) {
        $this->loginService = $loginService;
        $this->registerService = $registerService;
        $this->logoutService = $logoutService;
        $this->resendVerificationEmailService = $resendVerificationEmailService;
    }

    // login user
    public function login(LoginRequest $request)
    {
        $response = $this->loginService->execute($request->only(['email', 'password']));
        return ApiResponseClass::sendResponse(['user' => new AuthResource($response['user']),'token' => $response['token'],], 'Login Successful', 200);
    }

    // register user
    public function register(RegisterRequest $request)
    {
         $response= $this->registerService->execute($request->only(['name', 'email', 'password']));
         return ApiResponseClass::sendResponse(['user' => new AuthResource($response['user']),'token' => $response['token'],], 'User registered successfully', 200);
    }

    // logout user
    public function logout()
    {
        $this->logoutService->execute();
        return ApiResponseClass::sendResponse('Logout Successful', '', 200);
    }

    public function resendVerificationEmail()
    {
        try {
            $user = auth()->user();
            $response = $this->resendVerificationEmailService->execute($user);
            return ApiResponseClass::sendResponse(null, $response['message'], $response['status']);
        } catch (EmailAlreadyVerifiedException $e) {
            return ApiResponseClass::throw($e->getMessage(), $e->getStatusCode());
        }
    }
}
