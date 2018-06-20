<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerSubmit(Request $request)
    {
        return redirect()->home();
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginSubmit(Request $request){
        return redirect()->home();
    }

    public function forgotPw()
    {
        return view('auth.forgotpw');
    }

    public function forgotPwSubmit(Request $request){
        return redirect()->home();
    }

    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
    }
}
