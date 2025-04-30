<?php

namespace App\Services\User;

use App\Interfaces\UserRepositoryInterface;

class ShowUserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($id)
    {
        return $this->userRepository->getById($id);
    }

    public function getAllUsers()
    {
        return $this->userRepository->index();
    }
}
