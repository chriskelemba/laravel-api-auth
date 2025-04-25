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
}
