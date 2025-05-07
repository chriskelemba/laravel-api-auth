<?php

namespace App\Services\Auth;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;

class EmailVerificationService
{
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
            return $this->response(false, 'Email already verified');
        }

        if ($user->email_verification_token !== $token) {
            return $this->response(false, 'Invalid verification token');
        }

        $this->authRepository->verifyEmail($user);
        return $this->response(true, 'Email verified', $user);
    }

    public function resend($user)
    {
        if ($user->email_verified_at) {
            return $this->response(false, 'Email already verified');
        }

        $user->email_verification_token = $this->authRepository->generateEmailVerificationToken();
        $user->save();

        $this->emailService->sendVerificationEmail($user);

        return $this->response(true, 'Verification email resent');
    }

    private function response(bool $success, string $message, $user = null): array
    {
        return compact('success', 'message', 'user');
    }
}
