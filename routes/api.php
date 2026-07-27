<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\KintaiEntryController;

Route::get('employee_api',[EmployeeController::class,'index']);
Route::get('employee_api/search',[EmployeeController::class,'search']);
Route::get('employee_api/page',[EmployeeController::class,'page']);
Route::post('employee_api/add',[EmployeeController::class,'add']);
Route::post('employee_api/updateModal',[EmployeeController::class,'updateModal']);
Route::post('employee_api/edit',[EmployeeController::class,'edit']);

Route::get('kintai_entry_api',[KintaiEntryController::class,'index']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
