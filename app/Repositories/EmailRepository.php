<?php

namespace App\Repositories;

use App\Interfaces\EmailRepositoryInterface;
use App\Mail\EmailVerificationMail;
use App\Mail\PasswordResetMail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailRepository implements EmailRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function handle(array $data)
    {
        //
    }

    public function sendVerificationEmail($user)
    {
        $verificationUrl = url("/api/verify-email/{$user->id}/{$user->email_verification_token}");

        Mail::to($user->email)->queue(new EmailVerificationMail($user, $verificationUrl));
    }

    public function sendWelcomeEmail($user)
    {
        Mail::to($user->email)->queue(new WelcomeMail($user));
    }

    public function sendPasswordResetEmail($user, $token)
    {
        $resetUrl = url("/reset-password?token={$token}");

        // normal send
        // Mail::to($user->email)->send(new PasswordResetMail($user, $resetUrl,$token));

        // queue send
        Mail::to($user->email)->queue(new PasswordResetMail($user, $resetUrl, $token));
    }

    public function resendVerificationEmail($user)
    {
        if ($user->hasVerifiedEmail()) {
            return [
                'success' => false,
                'message' => 'Email already verified',
                'status' => 400
            ];
        }

        // Generate new token if needed
        if (empty($user->email_verification_token)) {
            $user->email_verification_token = Str::random(60);
            $user->save();
        }

        $verificationUrl = $this->generateVerificationUrl($user);
        Mail::to($user->email)->queue(new EmailVerificationMail($user, $verificationUrl));

        return [
            'success' => true,
            'message' => 'Verification email resent',
            'status' => 200
        ];
    }

    protected function generateVerificationUrl($user)
    {
        return url("/api/verify-email/{$user->id}/{$user->email_verification_token}");
    }
}
