<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Class\ApiResponseClass;
use App\Interfaces\AuthRepositoryInterface;

class AuthController extends Controller
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        try {
            $user = $this->authRepository->login($credentials);

            if (!$user) {
                return ApiResponseClass::sendResponse('Unauthorized', 'Invalid credentials', 401);
            }

            return ApiResponseClass::sendResponse($user, 'Login Successful', 200);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    public function register(Request $request)
    {
        try {
            $data = $request->only(['name', 'email', 'password']);
            $user = $this->authRepository->register($data);

            return ApiResponseClass::sendResponse($user, 'Registration Successful', 201);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    public function logout()
    {
        try {
            $this->authRepository->logout();
            return ApiResponseClass::sendResponse('Logout Successful', '', 200);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }
}
