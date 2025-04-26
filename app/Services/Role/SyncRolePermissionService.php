<?php

namespace App\Services\Role;

use App\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class SyncRolePermissionService
{
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function handle(array $data)
    {
        //
    }

    public function execute($roleId, array $permissions)
    {
        return $this->roleRepository->syncPermissionToRole($roleId, $permissions);
    }

    public function getRolePermissions($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);

        return $role;
    }
}
