<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KintaiMasterController;

Route::get('/', function () {
    return view('welcome');
});


/* ログイン */
Route::controller(LoginController::class)->group(function(){
    /* 未ログインのときにroute名:loginが処理される */
    Route::get('login','index')->name('login');
    Route::post('login/login','login');
    /* route(logout)のurlをlogoutにしている */
    Route::post('logout','logout')->name('logout');
});

/* 社員マスタ管理 */
Route::get('/employee_api',function(){
    return view('vue.employee_api');
})->middleware('auth');

/* 祝日マスタ管理 */
Route::get('/holiday',function(){
    Route::get('holiday','index')->middleware('auth');
});
/* 勤怠入力 */
Route::get('/kintai_entry_api',function(){
    return view('vue.kintai_entry_api');
})->middleware('auth');

/* 勤怠管理 */
Route::controller(KintaiMasterController::class)->group(function(){
    Route::get('kintai_master','index')->middleware('auth');
});


