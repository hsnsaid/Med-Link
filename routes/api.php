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
    Route::post('doctors/login',[DoctorController::class,'check']);
    Route::apiResource('doctors',DoctorController::class);
    Route::middleware('auth:sanctum')->post('doctors/profile',[DoctorController::class,'showAuthenticatedDoctor']);
    Route::middleware('auth:sanctum')->post('doctors/logout', [DoctorController::class, 'logout']);
    Route::patch('doctors/update/password/{doctor}',[DoctorController::class,'updatePassword']);

    Route::post('users/login',[UserController::class,'check']);
    Route::apiResource('users',UserController::class);
    Route::patch('users/update/password/{user}',[UserController::class,'updatePassword']);
    Route::middleware('auth:sanctum')->post('users/profile',[UserController::class,'showAuthenticatedUser']);
    Route::middleware('auth:sanctum')->post('/users/logout', [UserController::class, 'logout']);

    Route::apiResource('appointments',AppointmentController::class);
});