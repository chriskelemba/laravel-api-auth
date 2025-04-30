<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Interfaces\UserRepositoryInterface;
use App\Class\ApiResponseClass;

class UpdateUserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(array $data, $id)
    {
        DB::beginTransaction();

        try {
            $this->userRepository->update($data, $id);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            ApiResponseClass::rollback($ex);
        }

        return User::findOrFail($id);
    }
}
