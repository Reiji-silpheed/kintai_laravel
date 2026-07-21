<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function index(Request $request,Response $response):View
    {
        return view('auth.login');
    }
    public function open(LoginRequest $request,Response $response):RedirectResponse
    {
        $loginID=$request->input('loginID');
        $items=User::where('id',$loginID)->get();
        if($items->contains('role_cd','0')){
            session()->put('role_cd',0);
            return redirect('/kintai_entry_api');
        }
        else{
            session()->put('role_cd',1);
            return redirect('/kintai_master');
        }
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
