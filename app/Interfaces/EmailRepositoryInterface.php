<?php

namespace App\Interfaces;

interface EmailRepositoryInterface
{
    public function sendVerificationEmail($user);
    public function sendWelcomeEmail($user);
    public function sendPasswordResetEmail($user, $token);

    public function resendVerificationEmail($user);
}
