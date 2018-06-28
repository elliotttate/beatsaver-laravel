<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendEmailVerificationRequest;
use App\Models\User;
use Carbon\Carbon;
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
            'name'     => $request->input('username'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        auth()->login($newUser);
        event(new UserRegistered($newUser));

        return redirect()->route('profile');

    }

    /**
     * @param $token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail($token)
    {
        $user = auth()->user();
        if (!$user->isVerified() && $user->verification_code == trim($token)) {
            $user->verification_code = null;
            $user->save();
            return redirect()->route('profile')->with('status-success', 'Email successful validated!');
        }
        return redirect()->route('profile')->with('status-error', 'Invalid verification code!');
    }

    /**
     * @param ResendEmailVerificationRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmailResend(ResendEmailVerificationRequest $request)
    {
        event(new UserRegistered(auth()->user()));

        session(['last-verificatopn-sent' => Carbon::now()->addMinutes(2)]);
        return redirect()->route('profile')->with('status-success', 'Validation email sent');
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
    public function loginSubmit(LoginRequest $request)
    {

        $credentials = [
            'name'     => $request->input('username'),
            'password' => $request->input('password')
        ];

        $rememberMe = $request->input('remember');
        if (!auth()->attempt($credentials, $rememberMe)) {
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
    public function forgotPwSubmit(Request $request)
    {
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
