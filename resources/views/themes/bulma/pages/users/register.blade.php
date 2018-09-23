@extends('themes.bulma.layout')
@section('title', '- Register')

@section('content')
    <div class="content">
        <div style="display: flex;flex-direction: column;align-items: center;">
            <form style="max-width: 600px;width: 100%;" method="POST" action="{{ route('register.submit') }}">
                <h2>Create New Account</h2>
                {{ csrf_field() }}

                <article class="message is-info">
                    <div class="message-body">
                        Before you register please read our <a href="{{ route('legal.privacy') }}">privacy</a> page!
                    </div>
                </article>

                <div class="field">
                    <label class="label">Username <i>(max. 16 chars, only letters and numbers)</i></label>
                    <div class="control has-icons-left">
                        <input type="username" id="inputPassword" class="input" placeholder="Username" name="username" maxlength="16" required autofocus>
                        <span class="icon is-small is-left">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Email Address</label>
                    <div class="control has-icons-left">
                        <input type="email" id="inputEmail" class="input" placeholder="Email Address" name="email" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Password <i>(min. 8 chars)</i></label>
                    <div class="control has-icons-left">
                        <input type="password" id="inputPassword" class="input" placeholder="Password" name="password" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                </div>

                <div class="field">
                    <div class="control has-icons-left">
                        <input type="password" id="inputPasswordConfirm" class="input" placeholder="Password Confirm" name="password_confirmation" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                </div>

                <button class="button is-link is-fullwidth" type="submit">Sign Up</button>
            </form>
        </div>
    </div>
@endsection