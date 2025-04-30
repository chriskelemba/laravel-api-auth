<?php

namespace App\Services\User;

use App\Interfaces\UserRepositoryInterface;

class DeleteUserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($id)
    {
        $this->userRepository->delete($id);
    }
}
