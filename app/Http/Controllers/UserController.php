<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\User\UserService;

class UserController extends Controller
{

    public function __construct(
        private UserService $userService
    ) {}

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return sendApiResponse(UserResource::collection($users), '', 200);
    }

    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        return sendApiResponse(new UserResource($user), '', 200);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $this->userService->updateUser( $id, $request->only(['name', 'email', 'password']));
        return sendApiResponse('User Updated Successfully', '', 201);
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        return sendApiResponse('User Deleted Successfully', '', 204);
    }

    public function resetPassword(ResetPasswordRequest $request, $id)
    {
        $this->userService->resetPassword(
            $id,
            $request->old_password,
            $request->password
        );

        return sendApiResponse('Password Reset Successfully.', '', 200);
    }
}