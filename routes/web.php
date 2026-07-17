<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/employee_api',function(){
    return view('vue.employee_api');
});

Route::controller(LoginController::class)->group(function(){
    Route::get('login','index');
    Route::post('login/login','login');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
