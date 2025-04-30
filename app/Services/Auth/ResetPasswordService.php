<?php

namespace App\Services\Auth;

use App\Exceptions\Custom\InvalidResetTokenException;
use App\Repositories\PasswordRepository;
use Illuminate\Support\Facades\Hash;

class ResetPasswordService
{
    protected $passwordRepository;

    public function __construct(PasswordRepository $passwordRepository,)
    {
        $this->passwordRepository = $passwordRepository;
    }
    public function execute(string $token, string $newPassword,?string $currentPassword = null)
    {
        $tokenData = $this->passwordRepository->validateToken($token);

        if (!$tokenData['valid']) {
            throw new InvalidResetTokenException();
        }

        $this->passwordRepository->resetPassword(
            $tokenData['email'],
            Hash::make($newPassword),
        );

        return [
            'success' => true,
            'message' => 'Password reset successfully',
            'status' => 200
        ];
    }
}
