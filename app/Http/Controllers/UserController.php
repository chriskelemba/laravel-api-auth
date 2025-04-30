<?php

namespace App\Http\Controllers;

use App\Class\ApiResponseClass;
use App\Http\Resources\UserResource;
use App\Services\User\GetUserService;
use App\Http\Requests\UpdateUserRequest;
use App\Services\User\DeleteUserService;
use App\Services\User\ResetPasswordService;
use App\Services\User\UpdateUserService;
use App\Http\Requests\ResetPasswordRequest;

class UserController extends Controller
{
    private GetUserService $getUserService;
    private UpdateUserService $updateUserService;
    private DeleteUserService $deleteUserService;
    private ResetPasswordService $resetPasswordService;

    public function __construct(
        GetUserService $getUserService,
        UpdateUserService $updateUserService,
        DeleteUserService $deleteUserService,
        ResetPasswordService $resetPasswordService
    ) {
        $this->getUserService = $getUserService;
        $this->updateUserService = $updateUserService;
        $this->deleteUserService = $deleteUserService;
        $this->resetPasswordService = $resetPasswordService;
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

    public function resetPassword(ResetPasswordRequest $request, $id)
    {
        $this->resetPasswordService->execute(
            $id,
            $request->old_password,
            $request->password
        );

        return ApiResponseClass::sendResponse('Password reset successfully.', '', 200);
    }
}