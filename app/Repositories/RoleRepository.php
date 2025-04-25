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

    public function update(Role $role)
    {
        $role->update();
    }

    public function delete($id)
    {
        $role = Role::find($id);
        $role->delete();
    }
}
