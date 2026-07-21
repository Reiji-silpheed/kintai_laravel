<?php

Auth::routes();
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KintaiMasterController;

Route::get('/', function () {
    return view('welcome');
});

/* ログイン */
Route::controller(LoginController::class)->group(function(){
    Route::get('login','index')->middleware('auth');
    Route::post('login/login','open')->middleware('auth');
});

/* 社員マスタ管理 */
Route::get('/employee_api',function(){
    return view('vue.employee_api');
});

/* 勤怠入力 */
Route::get('/kintai_entry_api',function(){
    return view('vue.kintai_entry_api');
});

/* 勤怠管理 */
Route::controller(KintaiMasterController::class)->group(function(){
    Route::get('kintai_master','index')->middleware('auth');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
