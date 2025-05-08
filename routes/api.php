<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login')->middleware('not_blocked');
Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum', 'last_used_at']);

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/
Route::get('/verify-email/{id}/{token}', [EmailVerificationController::class, 'verify'])
    ->name('verification.verify');

Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail']);

/*
|--------------------------------------------------------------------------
| Password Reset Routes
|--------------------------------------------------------------------------
*/
Route::middleware('password.reset.limit')->group(function () {
    Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword'])
        ->name('password.email');
});

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Users
    Route::apiResource('/users', UserController::class)->middleware('role:admin');

    // Roles
    Route::apiResource('/roles', RoleController::class);
    Route::get('/roles/{roleId}/permissions', [RoleController::class, 'getRolePermissions']);
    Route::post('/roles/{roleId}/give-permissions', [RoleController::class, 'syncPermissionToRole']);

    // Permissions
    Route::apiResource('/permissions', PermissionController::class);
});
