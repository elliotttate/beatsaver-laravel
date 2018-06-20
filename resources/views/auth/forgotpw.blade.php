@extends('layout')
@section('title', '- Login/Register')

@section('content')
    <div class="container">
        <form class="form-signin" method="post" action="{{ route('forgotpw.submit') }}">
            {{ csrf_field() }}
            <h2 class="form-signin-heading">Forgot Password</h2><br>Don't forget to check your spam!<br>
            <label for="inputEmail" class="sr-only">EMail Address</label>
            <input type="email" name="username" id="inputEmail" class="form-control" placeholder="Email Address" required autofocus>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Send Email</button>
        </form>
    </div>
@endsection