@extends('themes.bulma-dark.layout')
@section('title', '- Privacy Policy')

@section('content')
    <div class="content">
        <h1>BeatSaver Privacy Policy</h1>

        <h2>Non-registered Users</h2>
        <p>This site uses session-cookies in order to track an authenticated user over multiple page requests.
            Due to technical reasons a cookie will also be created even if you are not a registered user. In that case no personal data is stored inside the cookie.</p>

        <h2>Registered Users</h2>
        <p>This site uses session-cookies in order to track an authenticated user over multiple page requests.
            Due to technical reasons a cookie will also be created even if you are not a registered user. In that case no personal data is stored inside the cookie.
            After a successful login an identifier will be written to the cookie until the session expires or the user is logged out.</p>
        
        <p>In order to register at BeatSaver we need three things:</p>
        <ul>
            <li>Username: will be <strong>publicly visible</strong> if you choose to upload a song</li>
            <li>Email: will be used for first time account validation, password resets and "new song notification" in case you subscribe to another users uploads.
                All emails will be sent from <strong>{{ config('mail.from.address') }}</strong></li>
            <li>Password: we store the password in an <strong>encrypted</strong> and salted hash (see <a href="https://en.wikipedia.org/wiki/Bcrypt" target="_blank">BCrypt</a>)</li>
        </ul>
    </div>
@endsection