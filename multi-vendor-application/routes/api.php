<?php

use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\Auth\VerificationController;
use App\Http\Controllers\API\V1\Vendor\VendorRegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post ('verify',[VerificationController::class, 'verify']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('vendor/register', [VendorRegisterController::class, 'register']);
    });
});
