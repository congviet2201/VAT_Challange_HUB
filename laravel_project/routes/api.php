<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'apiLogin']);

Route::middleware('auth.api')->group(function () {
    Route::get('me', [AuthController::class, 'apiMe']);
    Route::post('logout', [AuthController::class, 'apiLogout']);
});
