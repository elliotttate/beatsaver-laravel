@extends('themes.bulma.layout')
@section('title', '- Complete Password Reset')

@section('content')
    <div class="content">
        <div style="display: flex;flex-direction: column;align-items: center;">
            <form style="max-width: 600px;width: 100%;" method="post" action="{{ route('password.reset.complete.submit') }}">
                <h2>Forgot Password</h2>
                <h5>Reset your password.</h5>

                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field">
                    <label class="label">Email Address</label>
                    <div class="control has-icons-left">
                        <input type="email" name="email" id="inputEmail" class="input" placeholder="Email Address" required autofocus>
                        <span class="icon is-small is-left">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Password</label>
                    <div class="control has-icons-left">
                        <input type="password" name="password" id="inputPassword" class="input" placeholder="Password" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Password Confirm</label>
                    <div class="control has-icons-left">
                        <input type="password" name="password_confirmation" id="inputPasswordConfirm" placeholder="Password Confirm" class="input" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                </div>

                <button class="button is-primary is-fullwidth" type="submit">Reset</button>
            </form>
        </div>
    </div>
@endsection