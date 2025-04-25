<?php

namespace App\Interfaces;

use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface
{
    public function show($id);

    public function index();

    public function store(Role $role);

    public function update(Role $role);

    public function delete($id);
}
