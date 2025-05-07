<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Class\ApiResponseClass;
use App\Services\Auth\AuthService;
use App\Http\Resources\AuthResource;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\ResendVerificationEmailService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

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
        return ApiResponseClass::sendResponse(['user' => new AuthResource($response['user']),'token' => $response['token'],], 'Login Successful', 200);
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

    public function resendVerificationEmail(Request $request)
    {
        $userIdentifier = $request->input('email');
        $user = User::where('email', $userIdentifier)->first();
    
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
    
        $response = $this->emailVerificationService->execute($user);
        return ApiResponseClass::sendResponse(null, $response['message'], $response['status']);
    }
}
