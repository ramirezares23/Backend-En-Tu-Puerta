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

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource(
        'petitions',
        PetitionController::class
    )->except(['update']);

    Route::patch('petitions/{petition}',[PetitionController::class,'update']);

    Route::apiResource(
            'services',
            ServiceController::class
        );
    Route::apiResource(
            'users',
            UserController::class
        );

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
