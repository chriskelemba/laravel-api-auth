<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Str;

class AuthRepository implements AuthRepositoryInterface
{
    public function generateToken(User $user)
    {
        $tokenResult = $user->createToken('auth_token', ['*']);

        $token = $tokenResult->plainTextToken;

        $tokenModel = $user->tokens()->latest()->first();

        $tokenModel->forceFill([
            'last_used_at' => now(),
            'expires_at' => now()->addDays(30),
        ])->save();

        return $token;
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['email_verification_token'] = $this->generateEmailVerificationToken();

        $roleName = 'user';
        $role = Role::firstOrCreate(['name' => $roleName]);

        $user = User::create($data);

        $user->assignRole($role);

        // Login the user
        Auth::login($user);

        return $user;
    }


    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            return Auth::user();
        }

        return false;
    }


    public function logout()
    {
        $user = Auth::user();

        // If the user has an active token, update its last_used_at field
        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->forceFill([
                'last_used_at' => now(),
                'expires_at' => now() // Optionally set the token to expire now
            ])->save();
        }
    }

    public function verifyEmail(User $user)
    {
        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();
    }

    public function generateEmailVerificationToken()
    {
        return Str::random(60);
    }

}
