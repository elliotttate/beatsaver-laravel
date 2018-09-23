@extends('themes.default.layout')
@section('title', '- Complete Password Reset')

@section('content')
    <div class="container">
        <form class="form-signin" method="post" action="{{ route('password.reset.complete.submit') }}">
            {{ csrf_field() }}
            <h2 class="form-signin-heading">Forgot Password</h2><br>Don't forget to check your spam!<br>
            <label for="inputEmail" class="sr-only">EMail Address</label>
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email Address" required autofocus>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
            <input type="password" name="password_confirmation" id="inputPasswordConfirm" placeholder="Password Confirm" class="form-control"  required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Reset</button>
        </form>
    </div>
@endsection