<?php

namespace App\Http\Controllers;

use App\Class\ApiResponseClass;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Services\Permission\CreatePermissionService;
use App\Services\Permission\DeletePermissionService;
use App\Services\Permission\GetPermissionService;
use App\Services\Permission\UpdatePermissionService;

class PermissionController extends Controller
{
    private $createPermissionService;
    private $getPermissionService;
    private $updatePermissionService;
    private $deletePermissionService;

    public function __construct(
        CreatePermissionService $createPermissionService,
        GetPermissionService $getPermissionService,
        UpdatePermissionService $updatePermissionService,
        DeletePermissionService $deletePermissionService
    ) {
        $this->createPermissionService = $createPermissionService;
        $this->getPermissionService = $getPermissionService;
        $this->updatePermissionService = $updatePermissionService;
        $this->deletePermissionService = $deletePermissionService;
    }

    // get all permissions
    public function index()
    {
        $permissions = $this->getPermissionService->getAll();
        return ApiResponseClass::sendResponse(['permissions' => PermissionResource::collection($permissions)], 'Permissions fetched successfully', 200);
    }

    // get permission
    public function show($id)
    {
        $permission = $this->getPermissionService->execute($id);
        return ApiResponseClass::sendResponse(['permission' => new PermissionResource($permission)], 'Permission fetched successfully', 200);
    }

    // post role
    public function store(StorePermissionRequest $request)
    {
        $permission = $this->createPermissionService->execute($request->all());
        return ApiResponseClass::sendResponse(new PermissionResource($permission), 'Permission created successfully', 200);
    }

    // update role
    public function update(UpdatePermissionRequest $request, $id)
    {
        $permission = $this->updatePermissionService->execute($id, $request->only('name'));
        return ApiResponseClass::sendResponse(new PermissionResource($permission), 'Permission updated successfully', 200);
    }

    // delete role
    public function destroy($id)
    {
        $this->deletePermissionService->execute($id);
        return ApiResponseClass::sendResponse( 'Permission deleted successfully','', 200);
    }
}
