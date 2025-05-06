<?php

namespace App\Services\Auth;

use App\Exceptions\Custom\EmailNotVerifiedException;
use App\Exceptions\Custom\UnauthenticatedException;
use App\Interfaces\AuthRepositoryInterface;

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
            throw new UnauthenticatedException();
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
