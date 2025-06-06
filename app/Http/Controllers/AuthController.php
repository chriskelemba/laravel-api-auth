<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
use App\Http\Resources\AuthResource;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
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
        return sendApiResponse(['user' => new AuthResource($response['user']), 'token' => $response['token'],], 'Login Successful', 200);
    }


    // register user
    public function register(RegisterRequest $request)
    {
        $response = $this->authService->register($request->only(['name', 'email', 'password']));
        return sendApiResponse(['user' => new AuthResource($response['user']), 'token' => $response['token'],], 'User registered successfully', 200);
    }

    // logout user
    public function logout()
    {
        $this->authService->logout();
        return sendApiResponse('Logout Successful', '', 200);
    }

    public function resendVerificationEmail(Request $request)
    {
        $userIdentifier = $request->input('email');
        $user = User::where('email', $userIdentifier)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $response = $this->emailVerificationService->execute($user);
        return sendApiResponse(null, $response['message'], $response['status']);
    }

    public function user(Request $request)
    {
        $user = $this->authService->user();
        return sendApiResponse(new AuthResource($user), 'User fetched successfully', 200);
    }
}
