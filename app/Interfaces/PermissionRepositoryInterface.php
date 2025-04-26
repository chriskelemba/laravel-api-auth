<?php

namespace App\Interfaces;

use Spatie\Permission\Models\Permission;

interface PermissionRepositoryInterface
{
    public function index();
    public function show($id);
    public function store(Permission $permission);
    public function update($id,array $data);
    public function delete($id);
}
