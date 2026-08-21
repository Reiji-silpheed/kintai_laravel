<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HolidayController;
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
    if(session('role_cd')=='general'){
        return redirect('/login');
    }
    return view('vue.employee_api',['id'=>session('id')]);
})->middleware('auth');

/* 祝日マスタ管理 */
Route::controller(HolidayController::class)->group(function(){
    Route::get('holiday','index');
    Route::get('holiday/form','searchCondition');
    Route::get('holiday/page/{page}','page')->name('holiday/page');
    Route::get('holiday/page_front','pageFront');
    Route::get('holiday/page_next','pageNext');
    Route::post('holiday/add','add');
    Route::post('holiday/edit','edit');
    Route::post('holiday/delete','delete');
})->middleware('auth');
/* 勤怠入力 */
Route::get('/kintai_entry_api',function(){
    return view('vue.kintai_entry_api',['id'=>session('id')]);
})->middleware('auth');

/* 勤怠管理 */
Route::controller(KintaiMasterController::class)->group(function(){
    Route::get('kintai_master','index');
    Route::get('kintai_master/form','searchCondition');
    Route::get('kintai_master/page/{page}','page')->name('kintai_master/page');
    Route::get('kintai_master/page_front','page_front');
    Route::get('kintai_master/page_next','page_next');
    Route::post('kintai_master/approval','approval');
    Route::post('kintai_master/sendBack','sendBack');
    Route::post('kintai_master/excel','excel');
})->middleware('auth');


