<?php

namespace App\Interfaces;

interface PasswordRepositoryInterface
{
    public function createToken(string $email);
    public function validateToken(string $token);
    public function resetPassword(string $email, string $hashedPassword);
}
