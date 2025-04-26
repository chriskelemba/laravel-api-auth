<?php

namespace App\Services\Permission;

use App\Interfaces\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;

class GetPermissionService
{
    private $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function handle(array $data)
    {
        //
    }

    public function execute($id)
    {
        $permission = Permission::find($id);

        $this->permissionRepository->show($permission);

        return $permission;
    }

    public function getAll()
    {
        $permissions = Permission::all();

        $this->permissionRepository->index();

        return $permissions;
    }
}
