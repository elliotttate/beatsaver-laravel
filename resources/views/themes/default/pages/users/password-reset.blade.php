@extends('themes.default.layout')
@section('title', '- Reset Password')

@section('content')
    <div class="container">
        <form class="form-signin" method="post" action="{{ route('password.reset.request.submit') }}">
            {{ csrf_field() }}
            <h2 class="form-signin-heading">Forgot Password</h2><br>Don't forget to check your spam!<br>
            <label for="inputEmail" class="sr-only">EMail Address</label>
            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email Address" required autofocus>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Send Email</button>
        </form>
    </div>
@endsection