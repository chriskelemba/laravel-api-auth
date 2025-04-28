<?php

namespace App\Http\Controllers;

use App\Class\ApiResponseClass;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\LoginService;
use App\Services\Auth\LogoutService;
use App\Services\Auth\RegisterService;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    private $loginService;
    private $registerService;
    private $logoutService;

    public function __construct(LoginService $loginService, RegisterService $registerService, LogoutService $logoutService)
    {
        $this->loginService = $loginService;
        $this->registerService = $registerService;
        $this->logoutService = $logoutService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = $this->loginService->handle($request->only(['email', 'password']));

            if (!$user) {
                return ApiResponseClass::sendResponse('Unauthorized', 'Invalid credentials', 401);
            }

            return ApiResponseClass::sendResponse($user, 'Login Successful', 200);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->registerService->handle($request->only(['name', 'email', 'password']));

            return ApiResponseClass::sendResponse($user, 'Registration Successful', 201);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    public function logout()
    {
        try {
            $this->logoutService->handle();
            return ApiResponseClass::sendResponse('Logout Successful', '', 200);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }
}
