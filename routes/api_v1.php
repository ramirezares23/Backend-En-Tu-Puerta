<?php

use App\Http\Controllers\Api\V1\PetitionController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// users
// services
// petitions
// contracts

Route::middleware('auth:sanctum')
    ->apiResource(
        'petitions',
        PetitionController::class
    );
Route::middleware('auth:sanctum')
    ->apiResource(
        'services',
        ServiceController::class
    );
Route::middleware('auth:sanctum')
    ->apiResource(
        'users',
        UserController::class
    );

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
