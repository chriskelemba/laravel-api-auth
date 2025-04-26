<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// auth api
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// user api

// roles api
Route::apiResource('/roles',RoleController::class);

// role permission api
Route::get('/roles/{roleId}/permissions', [RoleController::class, 'getRolePermissions']);
Route::post('/roles/{roleId}/give-permissions', [RoleController::class, 'syncPermissionToRole']);

// permissions api
Route::apiResource('/permissions',PermissionController::class);
Route::apiResource('/users',UserController::class);