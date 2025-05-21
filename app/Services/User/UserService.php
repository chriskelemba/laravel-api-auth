<?php

namespace App\Services\User;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use App\Exceptions\Custom\NotFoundException;
use App\Exceptions\Custom\UnauthenticatedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Class\ApiResponseClass;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * Get all users
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws NotFoundException
     */
    public function getAllUsers()
    {
        // $users = User::all();
        $users = DB::table('users')->get();

        if ($users->isEmpty()) {
            throw new NotFoundException('Users');
        }

        return $this->userRepository->index();
    }

    /**
     * Get user by ID
     *
     * @param int $id
     * @return User
     * @throws NotFoundException
     */
    public function getUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new NotFoundException('User');
        }

        return $this->userRepository->getById($id);
    }

    /**
     * Update user
     *
     * @param int $id
     * @param array $data
     * @return User
     */
    public function updateUser($id, array $data)
    {
        DB::beginTransaction();

        $this->userRepository->update($data, $id);
        DB::commit();

        return User::findOrFail($id);
    }

    /**
     * Delete user
     *
     * @param int $id
     * @return void
     */
    public function deleteUser($id)
    {
        $this->userRepository->delete($id);
    }

    /**
     * Reset user password
     *
     * @param int $id
     * @param string $oldPassword
     * @param string $newPassword
     * @return void
     * @throws NotFoundException|UnauthenticatedException
     */
    public function resetPassword(int $id, string $oldPassword, string $newPassword): void
    {
        $user = User::find($id);

        if (!$user) {
            throw new NotFoundException('User');
        }

        if (!Hash::check($oldPassword, $user->password)) {
            throw new UnauthenticatedException('Old password is incorrect.');
        }

        $user->password = Hash::make($newPassword);
        $user->save();
    }
}