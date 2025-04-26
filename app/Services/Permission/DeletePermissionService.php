<?php

namespace App\Services\Permission;

use App\Interfaces\PermissionRepositoryInterface;

class DeletePermissionService
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
        $this->permissionRepository->delete($id);
    }
}
