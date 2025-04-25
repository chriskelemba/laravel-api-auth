<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Class\ApiResponseClass;
use App\Interfaces\UserRepositoryInterface;

class AuthController extends Controller
{
    private UserRepositoryInterface $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        try {
            $user = $this->userRepositoryInterface->login($credentials);

            if (!$user) {
                return ApiResponseClass::sendResponse('Unauthorized', 'Invalid credentials', 401);
            }

            return ApiResponseClass::sendResponse($user, 'Login Successful', 200);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    // public function logout()
    // {
    //     auth()->logout();

    //     return ApiResponseClass::sendResponse('Logout Successful', '', 200);
    // }
}
