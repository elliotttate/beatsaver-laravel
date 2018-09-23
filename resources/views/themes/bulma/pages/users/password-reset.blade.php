@extends('themes.default.layout')
@section('title', '- Reset Password')

@section('content')
    <div class="content">
        <div style="display: flex;flex-direction: column;align-items: center;">
            <form style="max-width: 600px;width: 100%;" method="post" action="{{ route('password.reset.request.submit') }}">
                <h2>Forgot Password</h2>
                <h5>Don't forget to check your spam!</h5>
                {{ csrf_field() }}

                <div class="field has-addons">
                    <div class="control is-expanded has-icons-left">
                        <input type="email" name="email" id="inputEmail" class="input" placeholder="Email Address" required autofocus>
                        <span class="icon is-small is-left">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>

                    <div class="control">
                        <button class="button is-link" type="submit">Send Email</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection