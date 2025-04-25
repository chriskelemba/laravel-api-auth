<?php

namespace App\Services\Role;

use App\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class UpdateRoleService
{
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    public function handle(array $data)
    {
        
    }

    public function execute($id, array $data)
    {
        $this->roleRepository->update($id, $data);

        return Role::findOrFail($id); // return updated role
    }

}
