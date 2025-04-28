<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class LoginService
{
    public function execute(array $credentials)
    {
        return Auth::attempt($credentials) ? Auth::user() : null;
    }
}
