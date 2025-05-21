<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Services\Permission\PermissionService;

class PermissionController extends Controller
{
    private $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    // get all permissions
    public function index()
    {
        $permissions = $this->permissionService->getAll();
        return sendApiResponse(['permissions' => PermissionResource::collection($permissions)], 'Permissions fetched successfully', 200);
    }

    // get permission
    public function show($id)
    {
        $permission = $this->permissionService->getById($id);
        return sendApiResponse(['permission' => new PermissionResource($permission)], 'Permission fetched successfully', 200);
    }

    // post role
    public function store(StorePermissionRequest $request)
    {
        $permission = $this->permissionService->create($request->all());
        return sendApiResponse(new PermissionResource($permission), 'Permission created successfully', 200);
    }

    // update role
    public function update(UpdatePermissionRequest $request, $id)
    {
        $permission = $this->permissionService->update($id, $request->only('name'));
        return sendApiResponse(new PermissionResource($permission), 'Permission updated successfully', 200);
    }

    // delete role
    public function destroy($id)
    {
        $this->permissionService->delete($id);
        return sendApiResponse('Permission deleted successfully', '', 200);
    }
}
