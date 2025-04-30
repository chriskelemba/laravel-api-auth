<?php

namespace App\Services\Auth;

use App\Exceptions\Custom\UserNotFoundException;
use App\Models\User;
use App\Repositories\EmailRepository;
use App\Repositories\PasswordRepository;

class ForgotPasswordService
{
    protected $passwordRepository;
    protected $emailRepository;

    public function __construct(
        PasswordRepository $passwordRepository,
        EmailRepository $emailRepository
    ) {
        $this->passwordRepository = $passwordRepository;
        $this->emailRepository = $emailRepository;
    }
    public function handle(array $data)
    {
        return;
    }
    public function execute(string $email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        $tokenData = $this->passwordRepository->createToken($email);
        // $resetUrl = url("/reset-password?token={$tokenData['token']}"); 
        // $this->emailRepository->sendPasswordResetEmail($user, $tokenData);
        $this->emailRepository->sendPasswordResetEmail($user, $tokenData['token']);

        return [
            'success' => true,
            'message' => 'Password reset link sent to your email',
            'status' => 200
        ];
    }
}
