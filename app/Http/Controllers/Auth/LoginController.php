<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function index(Request $request,Response $response):View
    {
        return view('auth.login');
    }
    public function login(LoginRequest $request,Response $response):RedirectResponse
    {
        return redirect('/employee_api');
    }
}
