<?php

namespace App\Interfaces;

use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface
{
    public function show($id);

    public function index();

    public function store(Role $role);

    public function update($id,array $data);

    public function delete($id);

    public function syncPermissionToRole($roleId,array $permissions);

    public function getRolePermissions($roleId);
}
