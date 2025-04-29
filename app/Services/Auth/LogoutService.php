<?php

namespace App\Services\Auth;

use App\Interfaces\AuthRepositoryInterface;

class LogoutService
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }
    public function execute()
    {
        $this->authRepository->logout();
    }
}
