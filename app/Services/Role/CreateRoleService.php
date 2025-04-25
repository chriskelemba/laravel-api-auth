<?php

namespace App\Services\Role;

use App\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class CreateRoleService
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

    public function execute($data)
    {
        $role = new Role([
            'name' => $data['name']
        ]);

        $this->roleRepository->store($role);

        return $role;
    }
}
