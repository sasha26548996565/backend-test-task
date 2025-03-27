<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CounterpartyController;

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware('auth:sanctum')->controller(CounterpartyController::class)
    ->prefix('counterparty')->group(function () {
        Route::get('/', 'getList');
        Route::post('/store', 'store');
    });
