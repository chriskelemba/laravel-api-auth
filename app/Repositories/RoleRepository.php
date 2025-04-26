<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{

    public function index()
    {
        return Role::all();
    }

    public function show($id)
    {
        return Role::find($id);
    }

    public function store(Role $role)
    {
        $role->save();
    }

    public function update( $id,array $data)
    {
        $role = Role::findOrFail($id);
        $role->update($data);
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
    }

    public function syncPermissionToRole($roleId,array $permissions)
    {
        $role = Role::findOrFail($roleId);
        $role->syncPermissions($permissions);

        $role->load('permissions');

        return $role;
    }

    public function getRolePermissions($roleId)
    {
        return Role::find($roleId)->permissions;
    }
}
