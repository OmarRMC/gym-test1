<?php

use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([], function () {
    Route::post('/signup', [AuthController::class, 'signUp']);
    Route::get('/verify-email', [AuthController::class, 'validateEmail'])->name('verification.email');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource("/user", UserController::class);   
});