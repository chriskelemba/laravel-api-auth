<?php

namespace App\Repositories;

use App\Interfaces\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return Permission::all();
    }

    public function show($id)
    {
        return Permission::find($id);
    }

    public function store(Permission $permission)
    {
        $permission->save();
    }

    public function update($id, array $data)
    {
        $permission = Permission::findOrFail($id);
        $permission->update($data);
    }

    public function delete($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
    }
}
