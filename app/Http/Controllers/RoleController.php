<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\SyncPermissionToRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RolePermissionResource;
use App\Http\Resources\RoleResource;
use App\Services\Role\RoleService;

class RoleController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    // get all roles
    public function index()
    {
        $roles = $this->roleService->getAll();
        return sendApiResponse(['roles' => RoleResource::collection($roles)],'Roles fetched successfully',200);
    }
    // get role
    public function show($id)
    {
        $role = $this->roleService->getById($id);
        return sendApiResponse(['role' => new RoleResource($role)], 'Role fetched successfully', 200);
    }
    // post role

    public function store(StoreRoleRequest $request)
    {
        $role = $this->roleService->create($request->all());
        return sendApiResponse(new RoleResource($role), 'Role created successfully', 200);
    }

    // update role

    public function update(UpdateRoleRequest $request,$id)
    {
        $role = $this->roleService->update($id, $request->only('name'));
        return sendApiResponse(new RoleResource($role), 'Role updated successfully', 200);
    }

    // delete role
    public function destroy($id)
    {
        $this->roleService->delete($id);
        return sendApiResponse( 'Role deleted successfully','', 200);
    }

    // sync permission to role
    public function syncPermissionToRole(SyncPermissionToRoleRequest $request,$roleId)
    {
        $permissions = $request->permissions;
        $syncedPermissions = $this->roleService->syncPermissions($roleId, $permissions);
        return sendApiResponse(['role' => new RolePermissionResource($syncedPermissions)], 'Permissions synced successfully', 200);
    }

    // get role permissions
    public function getRolePermissions($roleId)
    {
        $role = $this->roleService->getRolePermissions($roleId);
        return sendApiResponse([new RolePermissionResource($role)], 'Permissions fetched successfully', 200);
    }
}
