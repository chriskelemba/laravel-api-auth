<?php

namespace App\Services\Permission;

use App\Interfaces\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;

class UpdatePermissionService
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

    public function execute($id, array $data)
    {
        $this->permissionRepository->update($id, $data);

        return Permission::findOrFail($id);
    }
}
