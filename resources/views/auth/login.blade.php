@extends('layout')
@section('title', '- Login/Register')

@section('content')
    <div class="container">
        <form class="form-signin" method="post" action="{{ route('login.submit') }}">
            {{ csrf_field() }}
            <h2 class="form-signin-heading">Please sign in</h2>
            <label for="inputEmail" class="sr-only">Username</label>
            <input type="username" name="username" id="inputEmail" class="form-control" placeholder="Username" required autofocus>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        </form>
        <a href="{{ route('register.form') }}">
            <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
        </a>
        <a href="{{ route('password.reset.request.form') }}">
            <button class="btn btn-primary btn-block" type="submit">Forgot Password</button>
        </a>
    </div>
@endsection