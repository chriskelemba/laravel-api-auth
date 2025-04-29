<?php

namespace App\Services\User;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;

class UpdateUserService
{
    private $userRepository;
    
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute (array $data, $id)
    {
        $this->userRepository->update($id, $data);

        return User::findOrFail($id);
    }
}
