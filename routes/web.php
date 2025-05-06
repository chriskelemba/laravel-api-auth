<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/reset-password', function (Request $request) {
//     if (!$request->has('token')) {
//         abort(400, 'Token is required');
//     }
//     return view('auth.reset-password', [
//         'token' => $request->query('token')
//     ]);
// });
Route::get('/reset-password', [PasswordResetController::class, 'showResetForm']);

Route::post('/login', [AuthController::class, 'login']);