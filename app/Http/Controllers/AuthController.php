<?php

namespace App\Http\Controllers;

use App\Class\ApiResponseClass;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\LoginService;
use App\Services\Auth\LogoutService;
use App\Services\Auth\RegisterService;

class AuthController extends Controller
{
    private $loginService;
    private $registerService;
    private $logoutService;

    public function __construct(
        LoginService $loginService,
        RegisterService $registerService,
        LogoutService $logoutService
    ) {
        $this->loginService = $loginService;
        $this->registerService = $registerService;
        $this->logoutService = $logoutService;
    }

    // login user
    public function login(LoginRequest $request)
    {
        $user = $this->loginService->execute($request->only(['email', 'password']));
        return $user
            ? ApiResponseClass::sendResponse($user, 'Login Successful', 200)
            : ApiResponseClass::sendResponse('Unauthorized', 'Invalid credentials', 401);
    }

    // register user
    public function register(RegisterRequest $request)
    {
        $user = $this->registerService->execute($request->only(['name', 'email', 'password']));
        return ApiResponseClass::sendResponse($user, 'Registration Successful', 201);
    }

    // logout user
    public function logout()
    {
        $this->logoutService->execute();
        return ApiResponseClass::sendResponse('Logout Successful', '', 200);
    }
}
