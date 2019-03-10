<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\ConfirmPasswordResetRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\NewTokenRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendEmailVerificationRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\AccessToken;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Request;
use Password;

class UserController extends Controller
{
    const MAX_ACCESS_TOKENS = 4;

    public function register()
    {
        return view('master.page-register');
    }

    /**
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerSubmit(RegisterRequest $request)
    {
        if (User::where('email', sha1($request->input('email')))->first()) {
            return redirect()->back()->withErrors('Email is not available!');
        }

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
            return redirect()->route('profile')->with('status-success', 'Email verification successful!');
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
        return redirect()->route('profile')->with('status-success', 'Verification email sent');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('master.page-login');
    }

    /**
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginSubmit(LoginRequest $request)
    {
        if ($this->attemptLogin($request)) {
            return redirect()->intended('profile');
        }

        return redirect()->back()->withErrors('Invalid login or password');
    }

    public function loginAdmin()
    {
        return view('admin.auth.login');
    }

    /**
     * The login request for the admin panel
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAdminSubmit(LoginRequest $request)
    {
        if ($this->attemptLogin($request)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->withErrors('Invalid login or password');
    }

    /**
     * Returns true on a successful login attempt.
     *
     * @param Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $attempt = auth()->attempt(
            [
                'name' => $request->input('username'),
                'password' => $request->input('password')
            ],
            $request->input('remember')
        );

        if ($attempt) {
            return true;
        }

        return false;
    }

    /**
     * Request a password reset mail form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resetPassword()
    {
        return view('master.page-password-reset');
    }

    /**
     * @param ResetPasswordRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPasswordSubmit(ResetPasswordRequest $request)
    {
        // update legacy sha1 emails to plain text mails
        if ($user = User::where('email', sha1($request->input('email')))->first()) {
            $user->email = $request->input('email');
            $user->save();
        }

        Password::broker()->sendResetLink(['email' => $request->input('email')]);

        // always sent a positive response in order to prevent email fishing
        return redirect()->route('password.reset.request.form')->with('request-send', true);
    }

    /**
     * Complete your password request form.
     *
     * @param $token
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirmPasswordReset($token)
    {
        return view('master.page-password-reset-complete')->with(['token' => $token]);
    }

    /**
     * @param ConfirmPasswordResetRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmPasswordResetSubmit(ConfirmPasswordResetRequest $request)
    {
        $status = Password::broker()->reset($request->only(['password', 'password_confirmation', 'token', 'email']), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->setRememberToken(str_random(40));
            $user->save();
            auth()->login($user);
        });

        if ($status == PasswordBroker::PASSWORD_RESET) {
            return redirect()->route('profile');
        }
        return redirect()->route('password.reset.complete.form')->with('status-error', 'Reset failed. Please try again');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        return view('master.page-profile');
    }

    /**
     * @param UpdateEmailRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEmail(UpdateEmailRequest $request)
    {
        $user = auth()->user();
        if (User::where('email', sha1($request->input('email')))->where('id', '<>', $user->id)->first()) {
            return redirect()->back()->withErrors('Email is not available!');
        }

        if ($request->input('email_old') == $user->email || sha1($request->input('email_old')) == $user->email) {
            $user->email = $request->input('email');
            $user->save();
            event(new UserRegistered(auth()->user())); //@todo send email updated event
            return redirect()->route('profile')->with('status-success', 'Email successfully changed.');
        }
        return redirect()->route('profile')->with('status-error', 'Email could not be changed! Please try again.');
    }

    /**
     * @param UpdatePasswordRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();
        if (!Hash::check($request->input('password_old'), $user->password)) {
            return redirect()->back()->withErrors('Passwords do not match');
        }
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('profile')->with('status-success', 'Password successfully changed');
    }

    public function token()
    {
        $tokens = AccessToken::where('user_id', auth()->id())->get();
        return view('master.page-token')->with(['tokens' => $tokens, 'max' => static::MAX_ACCESS_TOKENS]);
    }

    public function tokenSubmit(NewTokenRequest $request)
    {

        $tokens = AccessToken::where('user_id', auth()->id())->get()->keyBy('id');

        if ($request->has('delete')) {
            $oldToken = $tokens->get($request->input('delete'));
            if ($oldToken) {
                $oldToken->delete();
            }
        } elseif ($request->has('new') && $tokens->count() < static::MAX_ACCESS_TOKENS) {
            auth()->user()->tokens()->save(new AccessToken([
                'token' => str_random(60),
                'type'  => $request->input('new'),
            ]));
        }

        return redirect()->route('profile.token');
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
