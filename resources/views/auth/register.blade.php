@extends('layout')
@section('title', '- Register')

@section('content')
    <div class="container">
        @if($errors->isNotEmpty())
            <ul>
        @foreach ($errors->all() as $message)
            <li>{{ $message }}</li>
        @endforeach
            </ul>
        @endif
        <form class="form-signin" method="POST" action="{{ route('register.submit') }}">
            {{ csrf_field() }}
            <h2 class="form-signin-heading">Create New Account</h2>
            <h3>Username (max. 16 chars, only letters and numbers)</h3>
            <input type="username" id="inputPassword" class="form-control" placeholder="Username" name="username" maxlength="16" required>
            <h3>Email address (used for password resets)</h3>
            <input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="email" required autofocus>
            <h3>Password (min. 8 chars)</h3>
            <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password" required autofocus>
            <input type="password" id="inputPasswordConfirm" class="form-control" placeholder="Password Confirm" name="password_confirmation" required autofocus>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
            <br>
        </form>

    </div>
@endsection