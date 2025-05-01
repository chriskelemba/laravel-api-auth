<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// auth api
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum', 'last_used_at']);

// mail apis
Route::get('/verify-email/{id}/{token}', [EmailVerificationController::class, 'verify'])
    ->name('verification.verify');

Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])
    ->middleware('auth:sanctum');

// Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);

Route::middleware('password.reset.limit')->group(function () {
    Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword'])->name('password.email');
    // Include other password reset related routes here if needed
});
// routes/api.php
// Route::post('/forgot-password', function (Request $request) {
//     return response()->json(['status' => 'OK']);
// })->withoutMiddleware(['api']);

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

// users api
Route::apiResource('/users', UserController::class);

// roles api
Route::apiResource('/roles', RoleController::class);

// role permission api
Route::get('/roles/{roleId}/permissions', [RoleController::class, 'getRolePermissions']);
Route::post('/roles/{roleId}/give-permissions', [RoleController::class, 'syncPermissionToRole']);

// permissions api
Route::apiResource('/permissions', PermissionController::class);
Route::apiResource('/users', UserController::class);
