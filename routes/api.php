<?php

use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\ChatSessionController;
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
    Route::get('doctor/stats',[DoctorController::class,'stats']);
    Route::middleware('auth:sanctum')->get('doctor/client', [DoctorController::class, 'client']);
    Route::middleware('auth:sanctum')->get('doctor/myStats', [DoctorController::class, 'myStats']);

    Route::post('users/login',[UserController::class,'check']);
    Route::apiResource('users',UserController::class);
    Route::patch('users/update/password/{user}',[UserController::class,'updatePassword']);
    Route::middleware('auth:sanctum')->post('users/profile',[UserController::class,'showAuthenticatedUser']);
    Route::middleware('auth:sanctum')->patch('/users/update/balance', [UserController::class, 'updateBalance']);
    Route::middleware('auth:sanctum')->patch('/users/balance/chat', [UserController::class, 'balanceToChat']);
    Route::middleware('auth:sanctum')->post('/users/logout', [UserController::class, 'logout']);

    Route::apiResource('appointments',AppointmentController::class);
    Route::get('appointments/doctor/{id}',[AppointmentController::class,'showDoctorAppointment']);
    Route::get('appointments/doctor/Scheduled/{id}',[AppointmentController::class,'showScheduledAppointment']);
    Route::middleware('auth:sanctum')->patch('appointments/scheduled/{appointment}',[AppointmentController::class,'scheduledAppointment']);
    Route::middleware('auth:sanctum')->get('appointments/scheduled/doctor',[AppointmentController::class,'scheduledUser']);
    Route::get('appointments/user/{user}',[AppointmentController::class,'userAppointment']);

    Route::apiResource('chatMessage',ChatController::class);
    Route::middleware('auth:sanctum')->post('/chatMessage/send/', [ChatController::class, 'sendMessage']);
    Route::middleware('auth:sanctum')->post('/chatSession/end/{session}', [ChatSessionController::class, 'end']);
    Route::middleware('auth:sanctum')->post('/chatSession/review/{session}', [ChatSessionController::class, 'review']);
    Route::middleware('auth:sanctum')->get('/chat/showDoctor', [ChatSessionController::class, 'showSaveDoctor']);
    Route::middleware('auth:sanctum')->get('/chat/showChat/{session}', [ChatController::class, 'showChat']);

    Route::get('admin/stats',[AdminController::class,'stats']);
    Route::post('admin/login',[AdminController::class,'login']);
    Route::middleware('auth:sanctum')->post('admin/logout', [AdminController::class, 'logout']);
    Route::post('admin/approve',[AdminController::class,'approve']);
    Route::get('admin/doctors',[DoctorController::class,'doctors']);
    Route::get('admin/circle',[AdminController::class,'circle']);
    Route::get('admin/diagram',[AdminController::class,'diagram']);
});