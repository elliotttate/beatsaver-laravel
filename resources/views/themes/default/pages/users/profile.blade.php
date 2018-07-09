@extends('themes.default.layout')
@section('title', '- Profile')

@section('content')
    <div class="container">
        @if(! auth()->user()->isVerified() )
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

        @if( auth()->user()->hasLegacyEmail() )
            <br/>
            <div class="alert alert-warning text-center" role="alert">
                <div>Your account was imported into the new system. Previously your email was stored as a SHA1 hash.</div>
                <div>The new system requires us to save your email in plain text. <strong>You may use the same email to update your account.</strong></div>
                <div>You may chose not to update your email, but <strong>we won't be able to send you password recovery</strong> mails or notifications (in case you subscribed to a user).</div>
            </div>
        @endif


        <div class="row">
            <div class="col-md-4 col-md-offset-1">
                <h2 class="form-signin-heading">Update</h2>
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