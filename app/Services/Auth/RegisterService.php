<?php

namespace App\Services\Auth;

use App\Interfaces\AuthRepositoryInterface;

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
}
