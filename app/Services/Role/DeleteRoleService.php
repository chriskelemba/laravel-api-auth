<?php

namespace App\Services\Role;

use App\Interfaces\RoleRepositoryInterface;

class DeleteRoleService
{
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    
    public function handle(array $data)
    {
        //
    }

    public function execute($id)
    {
        $this->roleRepository->delete($id);
    }
}
