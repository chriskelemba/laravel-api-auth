<?php

namespace App\Services\User;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use App\Exceptions\Custom\NotFoundException;

class ShowUserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new NotFoundException('User not found.');
        }

        return $this->userRepository->getById($id);
    }

    public function getAllUsers()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            throw new NotFoundException('No users found.');
        }

        return $this->userRepository->index();
    }
}
