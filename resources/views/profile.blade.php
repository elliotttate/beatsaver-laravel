@extends('layout')
@section('title', '- Profile')

@section('content')
    @if(! auth()->user()->isVerified())
        <br/>
        <div class="alert alert-warning text-center" role="alert">
            Your account is not verified. You won't be able to upload songs until you verify your account!
            @if(session('last-verificatopn-sent') <= \Carbon\Carbon::now())
                <form action="{{ route('register.verify.resend') }}" method="post">
                    {{ csrf_field() }}
                    <button name="resend-email" value="1" type="submit" class="btn btn-default">Resend verification email</button>
                </form>
            @else
                <b>You recently requested a resend. Please wait a few minutes.</b>
            @endif
        </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-1">
                <h2 class="form-signin-heading">Update/Activate Email</h2>
                <form class="form-signin" method="post" action="{{ route('profile.update.email') }}">
                    {{ csrf_field() }}
                    <input type="text" name="email_old" id="inputEmail" class="form-control" placeholder="Old Email" required>
                    <input type="text" name="email" id="inputNewEmail" class="form-control" placeholder="New Email" required>
                    <input type="text" name="email_confirmation" id="inputNewEmailConfirm" class="form-control" placeholder="New Email Confirm" required><br/>
                    <button class="btn btn-primary btn-block" type="submit">Update Email</button>
                </form>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <h2 class="form-signin-heading">Change Password</h2>
                <form class="form-signin" method="post" action="{{ route('profile.update.password') }}">
                    {{ csrf_field() }}
                    <input type="password" id="inputOldPassword" class="form-control" placeholder="Old Password" name="password_old" required>
                    <input type="password" id="inputNewPassword" class="form-control" placeholder="New Password" name="password" required>
                    <input type="password" id="inputNewPasswordConfirm" class="form-control" placeholder="New Password Confirm" name="password_confirmation" required><br/>
                    <button class="btn btn-primary btn-block" type="submit">Update Password</button>
                </form>

            </div>
        </div>
    </div>
@endsection