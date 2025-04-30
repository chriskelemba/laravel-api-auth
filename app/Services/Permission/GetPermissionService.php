<?php

namespace App\Services\Permission;

use Spatie\Permission\Models\Permission;
use App\Exceptions\Custom\NotFoundException;
use App\Interfaces\PermissionRepositoryInterface;

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

        if(!$permission) {
            throw new NotFoundException('Permission');
        }

        $this->permissionRepository->show($permission);

        return $permission;
    }

    public function getAll()
    {
        $permissions = Permission::all();

        if ($permissions->isEmpty()) {
            throw new NotFoundException('Permissions');
        }

        $this->permissionRepository->index();

        return $permissions;
    }
}
