<?php

namespace App\Http\Controllers;

use App\Class\ApiResponseClass;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\Role\CreateRoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $createRoleService;

    public function __construct(CreateRoleService $createRoleService)
    {
        $this->createRoleService = $createRoleService;
    }

    // post role

    public function store(StoreRoleRequest $request)
    {
        $post = $this->createRoleService->execute($request->all());
        return ApiResponseClass::sendResponse(new RoleResource($post), 'Role created successfully', 200);
    }
}
