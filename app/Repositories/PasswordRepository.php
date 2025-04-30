<?php

namespace App\Repositories;

use App\Interfaces\PasswordRepositoryInterface;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
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
        $user = User::where('email', $email)->first();

        // Prevent password reuse (compares new hash with existing hash)
        if (Hash::check($user->password, $hashedPassword)) {
            return [
                'success' => false,
                'message' => 'New password cannot be same as current password',
                'status' => 422
            ];
        }

        $user->update(['password' => $hashedPassword]);
        PasswordResetToken::where('email', $email)->delete();

        return [
            'success' => true,
            'message' => 'Password reset successfully',
            'status' => 200
        ];
    }
    // public function resetPassword(string $email, string $hashedPassword, ?string $currentPassword = null)
    // {
    //     $user = User::where('email', $email)->first();

    //     // Verify current password if provided
    //     if ($currentPassword && !Hash::check($currentPassword, $user->password)) {
    //         return [
    //             'success' => false,
    //             'message' => 'Current password is incorrect',
    //             'status' => 422
    //         ];
    //     }

    //     // Check if new password is different
    //     if (Hash::check($currentPassword, $hashedPassword)) {
    //         return [
    //             'success' => false,
    //             'message' => 'New password must be different from current password',
    //             'status' => 422
    //         ];
    //     }

    //     // Update password
    //     $user->update(['password' => $hashedPassword]);
    //     PasswordResetToken::where('email', $email)->delete();

    //     return [
    //         'success' => true,
    //         'message' => 'Password reset successfully',
    //         'status' => 200
    //     ];
    // }
}
