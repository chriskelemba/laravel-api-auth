<?php

namespace App\Services\Role;

use App\Exceptions\Custom\NotFoundException;
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

        if (!$role) {
            throw new NotFoundException('Role not found.');
        }

        $this->roleRepository->show($role);

        return $role;
    }

    public function getAll()
    {
        $roles = Role::all();

        if ($roles->isEmpty()) {
            throw new NotFoundException('No roles found.');
        }
        
        $this->roleRepository->index();

        return $roles;
    }
}
