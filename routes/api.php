<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// roles api
Route::apiResource('/roles',RoleController::class);
Route::get('/roles/{roleId}/permissions', [RoleController::class, 'getRolePermissions']);
Route::post('/roles/{roleId}/give-permissions', [RoleController::class, 'syncPermissionToRole']);

// permissions api
Route::apiResource('/permissions',PermissionController::class);
Route::apiResource('/users',UserController::class);

Route::post('/login', [AuthController::class, 'login']);