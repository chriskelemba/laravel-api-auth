<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// roles api
Route::apiResource('/roles',RoleController::class);

// permissions api
Route::apiResource('/permissions',PermissionController::class);