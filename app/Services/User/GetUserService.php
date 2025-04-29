<?php

namespace App\Services\User;

use App\Interfaces\UserRepositoryInterface;

class GetUserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute()
    {
        return $this->userRepository->index();
    }
}
