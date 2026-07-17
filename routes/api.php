<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::get('employee_api',[EmployeeController::class,'index'])->middleware('auth');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
