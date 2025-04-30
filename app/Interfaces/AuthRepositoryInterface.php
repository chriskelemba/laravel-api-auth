<?php

namespace App\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function generateToken(User $user);
    public function register(array $data);

    public function login(array $credentials);

    public function logout();
    public function verifyEmail(User $user);
    // public function generateEmailVerificationToken(User $user);
    public function generateEmailVerificationToken();
}