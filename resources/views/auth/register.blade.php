@extends('layout')
@section('title', '- Register')

@section('content')
    <div class="container">
        <form class="form-signin" method="POST" action="{{ route('register.submit') }}">
            {{ csrf_field() }}
            <h2 class="form-signin-heading">Create New Account</h2>
            <b>All form fields are required, below are some details on how your information is stored</b>
            <ul>
                <li>An email is dispatched to the provided email to set your password, it will be coming from no-reply@beatsaver.com with the Subject: BeatSaver: New Password <b>Hotmail,Live,Yahoo has
                        known issues not accepting my mail</b></li>
                <li>E-Mail Addresses are stored as SHA1 in the database</li>
                <li>Usernames can only be letters and numbers, No spaces or special symbols, will be lowercased and limited to a max length of 16</li>
                <li>Passwords are stored using <a href="https://en.wikipedia.org/wiki/Bcrypt">BCrypt</a> with a cost of 14 and a random per user salt
            </ul>
            <h3>Email address</h3>
            <input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="email" required autofocus>
            <h3>Username</h3>
            <input type="username" id="inputPassword" class="form-control" placeholder="Username" name="username" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
            <br>
        </form>

    </div>
@endsection