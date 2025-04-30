<?php

namespace App\Repositories;

use App\Interfaces\PasswordRepositoryInterface;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PasswordRepository implements PasswordRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createToken(string $email)
    {
        $token = Str::random(60);
        $expiresAt = Carbon::now()->addHours(2);

        PasswordResetToken::updateOrCreate(
            ['email' => $email],
            [
                'token' => $token,
                'created_at' => now(),
                'expires_at' => $expiresAt
            ]
        );

        return [
            'token' => $token,
            'expires_at' => $expiresAt
        ];
    }

    public function validateToken(string $token)
    {
        $record = PasswordResetToken::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        return [
            'valid' => (bool) $record,
            'email' => $record->email ?? null
        ];
    }

    public function resetPassword(string $email, string $hashedPassword)
    {
        User::where('email', $email)
            ->update(['password' => $hashedPassword]);

        PasswordResetToken::where('email', $email)->delete();
    }
}
