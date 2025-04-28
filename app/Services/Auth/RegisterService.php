<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterService
{
    public function execute(array $data)
    {
        if (User::where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages([
                'email' => ['Email already exists.']
            ]);
        }
        
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }
}
