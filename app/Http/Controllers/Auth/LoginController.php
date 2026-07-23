<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;


    public function index()
    {
        return view('auth.login');
    }


    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->loginEmail)->first();

        /* ログイン済みであることを認識させる */
        Auth::login($user);
        session()->put('role_cd',$user['role_cd']);

        if ($user->role_cd == 0) {
            return redirect('/kintai_entry_api');
        }

        return redirect('/kintai_master');
    }

    protected function loggedOut(Request $request)
    {
        return redirect('/login');
    }
}
