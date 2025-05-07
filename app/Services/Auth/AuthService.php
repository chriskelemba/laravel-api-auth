<?php

namespace App\Services\Auth;

use App\Exceptions\Custom\EmailAlreadyVerifiedException;
use App\Exceptions\Custom\EmailNotVerifiedException;
use App\Exceptions\Custom\UnauthenticatedException;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;

class AuthService
{
    protected $authRepository;
    protected $emailRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        EmailRepositoryInterface $emailRepository
    ) {
        $this->authRepository = $authRepository;
        $this->emailRepository = $emailRepository;
    }

    // Handle user registration
    public function register(array $data): array
    {
        $user = $this->authRepository->register($data);

        $this->emailRepository->sendVerificationEmail($user);
        $this->emailRepository->sendWelcomeEmail($user);

        $token = $this->authRepository->generateToken($user);

        $user->token = $token;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    // Handle user login
    public function login(array $credentials): array
    {
        $user = $this->authRepository->login($credentials);

        if (!$user) {
            throw new UnauthenticatedException();
        }

        $token = $this->authRepository->generateToken($user);

        return [
            'user' => $user,
            'token' => $token,
            'email_verified' => $user->hasVerifiedEmail(),
        ];
    }


    // Handle user logout
    public function logout(): void
    {
        $this->authRepository->logout();
    }

    // handle resend verification email

    public function resendVerificationEmail($user)
    {
        if ($user->hasVerifiedEmail()) {
            throw new EmailAlreadyVerifiedException();
        }

        return $this->emailRepository->resendVerificationEmail($user);
    }
}
