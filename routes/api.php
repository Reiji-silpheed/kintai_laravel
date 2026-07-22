<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\KintaiEntryController;

Route::get('employee_api',[EmployeeController::class,'index']);

Route::get('kintai_entry_api',[KintaiEntryController::class,'index']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
