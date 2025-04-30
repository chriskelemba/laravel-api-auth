<?php

namespace App\Services\Auth;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;
use App\Interfaces\EmailServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterService
{

    private $authRepository;
    protected $emailService;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        EmailRepositoryInterface $emailService)
    {
        $this->authRepository = $authRepository;
        $this->emailService = $emailService;
    }

    public function execute(array $data): array
    {
        // Register the user
        $user = $this->authRepository->register($data);

        // Send verification email
        $this->emailService->sendVerificationEmail($user);
        
        // Send welcome email
        $this->emailService->sendWelcomeEmail($user);

        // Generate token for registered user
        $token = $this->authRepository->generateToken($user);

        $user->token = $token;
        
        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
