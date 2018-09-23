@extends('themes.default.layout')
@section('title', '- Register')

@section('content')
    <div class="container">
        <h1 class="form-signin-heading text-center">Create New Account</h1>
        <h2 class="alert-info text-center">Before you register please read our <a href="{{ route('legal.privacy') }}">privacy</a> page!</h2>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
            <form class="form-signin" method="POST" action="{{ route('register.submit') }}">
                {{ csrf_field() }}
                <h3>Username (max. 16 chars, only letters and numbers)</h3>
                <input type="username" id="inputPassword" class="form-control" placeholder="Username" name="username" maxlength="16" required autofocus>
                <h3>Email address</h3>
                <input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="email" required>
                <h3>Password (min. 8 chars)</h3>
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password" required><br/>
                <input type="password" id="inputPasswordConfirm" class="form-control" placeholder="Password Confirm" name="password_confirmation" required>
                <br/>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
                <br>
            </form></div>
        </div>



    </div>
@endsection