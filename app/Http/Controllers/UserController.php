<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    /**
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerSubmit(RegisterRequest $request)
    {
        $newUser = User::create([
           'name' => $request->input('username'),
           'email' => $request->input('email'),
           'password' => Hash::make($request->input('password')),
        ]);

        auth()->login($newUser);
        event(new UserRegistered($newUser));

        return redirect()->route('profile');

    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail(Request $request)
    {
        return redirect()->home();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginSubmit(LoginRequest $request){

        $credentials = [
            'name'    => $request->input('username'),
            'password' => $request->input('password')
        ];

        $rememberMe = $request->input('remember');
        if(!auth()->attempt($credentials,$rememberMe)){
            return redirect()->back()->withErrors('Invalid login or password');
        };

        return redirect()->intended('profile');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forgotPw()
    {
        return view('auth.forgotpw');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forgotPwSubmit(Request $request){
        return redirect()->home();
    }

    public function profile()
    {
        return view('profile');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        return redirect()->home();
    }
}
