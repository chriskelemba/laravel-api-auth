<?php

namespace App\Services\Permission;

use App\Exceptions\Custom\NotFoundException;
use App\Interfaces\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    private $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    // Create a new permission
    public function create(array $data)
    {
        $permission = new Permission([
            'name' => $data['name']
        ]);

        $this->permissionRepository->store($permission);

        return $permission;
    }

    // Get a single permission by ID
    public function getById($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            throw new NotFoundException('Permission');
        }

        $this->permissionRepository->show($permission);

        return $permission;
    }

    // Get all permissions
    public function getAll()
    {
        $permissions = Permission::all();

        if ($permissions->isEmpty()) {
            throw new NotFoundException('Permissions');
        }

        $this->permissionRepository->index();

        return $permissions;
    }

    // Update a permission
    public function update($id, array $data)
    {
        $this->permissionRepository->update($id, $data);

        return Permission::findOrFail($id);
    }

    // Delete a permission
    public function delete($id)
    {
        $this->permissionRepository->delete($id);
    }
}
