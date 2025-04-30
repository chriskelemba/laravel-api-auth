<?php

namespace App\Services\Auth;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;
use App\Interfaces\EmailServiceInterface;

class EmailVerificationService
{
    public function handle(array $data)
    {
        //
    }

    protected $authRepository;
    protected $emailService;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        EmailRepositoryInterface $emailService
    ) {
        $this->authRepository = $authRepository;
        $this->emailService = $emailService;
    }

    public function verify($user, $token)
    {
        if ($user->email_verified_at) {
            return [
                'success' => false,
                'message' => 'Email already verified',
                'user' => null
            ];
        }

        if ($user->email_verification_token !== $token) {
            return [
                'success' => false,
                'message' => 'Invalid verification token',
                'user' => null
            ];
        }

        $this->authRepository->verifyEmail($user);

        return [
            'success' => true,
            'message' => 'Email successfully verified',
            'user' => $user
        ];
    }

    public function resend($user)
    {
        if ($user->email_verified_at) {
            return [
                'success' => false,
                'message' => 'Email already verified'
            ];
        }

        $token = $this->authRepository->generateEmailVerificationToken();
        $user->email_verification_token = $token;
        $user->save();

        $this->emailService->sendVerificationEmail($user);

        return [
            'success' => true,
            'message' => 'Verification email resent'
        ];
    }
}
