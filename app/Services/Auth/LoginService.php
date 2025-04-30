<?php

namespace App\Services\Auth;

use App\Exceptions\Custom\EmailNotVerifiedException;
use App\Exceptions\Custom\UnauthorizedException;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }
    public function execute(array $credentials)
    {
        $user = $this->authRepository->login($credentials);

        if (!$user) {
            throw new UnauthorizedException();
        }

        if (!$user->hasVerifiedEmail()) {
            throw new EmailNotVerifiedException();
        }
        $token = $this->authRepository->generateToken($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }


}
