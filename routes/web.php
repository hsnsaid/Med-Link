<?php

use App\Http\Controllers\Api\V1\DoctorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::view('test','test');
Route::post('test',[DoctorController::class,'store']);