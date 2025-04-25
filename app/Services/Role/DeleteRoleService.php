<?php

namespace App\Services\Role;

use App\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class DeleteRoleService
{
    public function handle(array $data)
    {
        //
    }

    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function execute($id)
    {
        $this->roleRepository->delete($id);
    }
}
