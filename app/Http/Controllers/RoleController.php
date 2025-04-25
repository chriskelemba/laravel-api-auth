<?php

namespace App\Http\Controllers;

use App\Class\ApiResponseClass;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\Role\CreateRoleService;
use App\Services\Role\DeleteRoleService;
use App\Services\Role\GetRoleService;
use App\Services\Role\UpdateRoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $createRoleService;
    private $getRoleService;

    private $updateRoleService;

    private $deleteRoleService;

    public function __construct(CreateRoleService $createRoleService,GetRoleService $getRoleService,UpdateRoleService $updateRoleService,DeleteRoleService $deleteRoleService)
    {
        $this->createRoleService = $createRoleService;
        $this->getRoleService = $getRoleService;
        $this->updateRoleService = $updateRoleService;
        $this->deleteRoleService = $deleteRoleService;
    }

    // get all roles
    public function index()
    {
        $roles = $this->getRoleService->getAll();
        return ApiResponseClass::sendResponse(RoleResource::collection($roles), 'Roles fetched successfully', 200);
    }
    // get role
    public function show($id)
    {
        $role = $this->getRoleService->execute($id);
        return ApiResponseClass::sendResponse(new RoleResource($role), 'Role fetched successfully', 200);
    }
    // post role

    public function store(StoreRoleRequest $request)
    {
        $role = $this->createRoleService->execute($request->all());
        return ApiResponseClass::sendResponse(new RoleResource($role), 'Role created successfully', 200);
    }

    // update role

    public function update(UpdateRoleRequest $request,$id)
    {
        $role = $this->updateRoleService->execute($id, $request->only('name'));
        return ApiResponseClass::sendResponse(new RoleResource($role), 'Role updated successfully', 200);
    }

    // delete role 

    public function destroy($id)
    {
        $this->deleteRoleService->execute($id);
        return ApiResponseClass::sendResponse( 'Role deleted successfully','', 200);
    }
}
