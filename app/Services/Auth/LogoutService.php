<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class LogoutService
{
    public function execute()
    {
        Auth::logout();
    }
}
