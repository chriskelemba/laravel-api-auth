<?php

namespace App\Services\Role;

use App\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class GetRoleService
{
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }


    public function execute($id)
    {
        $role = Role::find($id);

        $this->roleRepository->show($role);

        return $role;
    }

    public function getAll()
    {
        $roles = Role::all();

        $this->roleRepository->index();

        return $roles;
    }
}
