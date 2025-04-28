<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public function handle(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }
}
