<?php

use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\DoctorController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::group(['prefix'=>'v1'],function(){
    Route::apiResource('doctors',DoctorController::class);
    Route::apiResource('appointments',AppointmentController::class);
    Route::apiResource('users',UserController::class);
    Route::post('users/login',[UserController::class,'check']);
    Route::patch('users/update/password/{user}',[UserController::class,'updatePassword']);
    Route::middleware('auth:sanctum')->post('users/profile',[UserController::class,'showAuthenticatedUser']);
    Route::middleware('auth:sanctum')->post('/users/logout', [UserController::class, 'logout']);
});