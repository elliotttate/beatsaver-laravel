@extends('themes.bulma.layout')
@section('title', '- Login/Register')

@section('content')
    <div class="content">
        <div style="display: flex;flex-direction: column;align-items: center;">
            <form style="max-width: 600px;width: 100%;" method="post" action="{{ route('login.submit') }}">
                <h2>Sign In</h2>
                {{ csrf_field() }}

                <div class="field">
                    <div class="control has-icons-left">
                        <input type="username" name="username" id="inputEmail" class="input" placeholder="Username" required autofocus>
                        <span class="icon is-small is-left">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                </div>

                <div class="field">
                    <div class="control has-icons-left">
                        <input type="password" name="password" id="inputPassword" class="input" placeholder="Password" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                </div>
                
                <button class="button is-primary is-fullwidth" type="submit">Sign in</button>
            </form>

            <br />
            <div>
                <a href="{{ route('register.form') }}" class="button">Register</a>
                <a href="{{ route('password.reset.request.form') }}" class="button">Forgot Password</a>
            </div>
        </div>
    </div>
@endsection