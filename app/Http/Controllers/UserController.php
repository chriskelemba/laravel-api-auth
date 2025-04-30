<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\User\GetUserService;
use App\Services\User\UpdateUserService;
use App\Services\User\DeleteUserService;
use App\Class\ApiResponseClass;

class UserController extends Controller
{
    private GetUserService $getUserService;
    private UpdateUserService $updateUserService;
    private DeleteUserService $deleteUserService;

    public function __construct(
        GetUserService $getUserService,
        UpdateUserService $updateUserService,
        DeleteUserService $deleteUserService
    ) {
        $this->getUserService = $getUserService;
        $this->updateUserService = $updateUserService;
        $this->deleteUserService = $deleteUserService;
    }

    public function index()
    {
        $users = $this->getUserService->execute();
        return ApiResponseClass::sendResponse(UserResource::collection($users), '', 200);
    }

    public function show($id)
    {
        $user = $this->getUserService->getById($id);
        return ApiResponseClass::sendResponse(new UserResource($user), '', 200);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $this->updateUserService->execute($request->only(['name', 'email', 'password']), $id);
        return ApiResponseClass::sendResponse('User Updated Successfully', '', 201);
    }    

    public function destroy($id)
    {
        $this->deleteUserService->execute($id);
        return ApiResponseClass::sendResponse('User Deleted Successfully', '', 204);
    }
}