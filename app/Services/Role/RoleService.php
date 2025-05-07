<?php

namespace App\Services\Role;

use App\Exceptions\Custom\NotFoundException;
use App\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class RoleService
{
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    // Create a new role
    public function create(array $data)
    {
        $role = new Role(['name' => $data['name']]);
        $this->roleRepository->store($role);

        return $role;
    }

    // Get a single role by ID
    public function getById($id)
    {
        $role = Role::find($id);

        if (!$role) {
            throw new NotFoundException('Role');
        }

        $this->roleRepository->show($role);

        return $role;
    }

    // Get all roles
    public function getAll()
    {
        $roles = Role::all();

        if ($roles->isEmpty()) {
            throw new NotFoundException('Roles');
        }

        $this->roleRepository->index();

        return $roles;
    }

    // Update a role
    public function update($id, array $data)
    {
        $this->roleRepository->update($id, $data);
        return Role::findOrFail($id);
    }

    // Delete a role
    public function delete($id)
    {
        $this->roleRepository->delete($id);
    }

    // Sync permissions to a role
    public function syncPermissions($roleId, array $permissions)
    {
        return $this->roleRepository->syncPermissionToRole($roleId, $permissions);
    }

    // Get role with permissions
    public function getRolePermissions($roleId)
    {
        return Role::with('permissions')->findOrFail($roleId);
    }
}
