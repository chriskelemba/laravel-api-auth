<?php

namespace App\Services\Auth;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterService
{

    private $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function execute(array $data): array
    {
        // Register the user
        $user = $this->authRepository->register($data);

        // Generate token for registered user
        $token = $this->authRepository->generateToken($user);

        $user->token = $token;
        
        return [
            'user' => $user,
            'token' => $token,
        ];
    }
    // public function execute(array $data)
    // {
    //     if (User::where('email', $data['email'])->exists()) {
    //         throw ValidationException::withMessages([
    //             'email' => ['Email already exists.']
    //         ]);
    //     }
        
    //     $data['password'] = Hash::make($data['password']);
    //     return User::create($data);
    // }
}
