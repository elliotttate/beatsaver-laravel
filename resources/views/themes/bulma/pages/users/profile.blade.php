@extends('themes.bulma.layout')
@section('title', '- Profile')

@section('content')
    <div class="content">
        @if(! auth()->user()->isVerified() )
            <article class="message is-danger">
                <div class="message-body">
                    <p>Your account is not verified. You won't be able to upload songs until you verify your account!</p>
                    
                    @if(session('last-verificatopn-sent') <= \Carbon\Carbon::now())
                        <form action="{{ route('register.verify.resend') }}" method="post">
                            {{ csrf_field() }}
                            <button name="resend-email" value="1" type="submit" class="button">Resend verification email</button>
                        </form>
                    @else
                        <p><b>You recently requested a resend. Please wait a few minutes.</b></p>
                    @endif
                </div>
            </article>
        @endif

        @if( auth()->user()->hasLegacyEmail() )
            <article class="message is-danger">
                <div class="message-body">
                    <p>Your account was imported into the new system. Previously your email was stored as a SHA1 hash.</p>
                    <p>The new system requires us to save your email in plain text. <strong>You may use the same email to update your account.</strong></p>
                    <p>You may chose not to update your email, but <strong>we won't be able to send you password recovery</strong> mails or notifications (in case you subscribed to a user).</p>
                </div>
            </article>
        @endif

        <div class="columns">
            <div class="column">
                <h2>Update Email</h2>
                <form class="form-signin" method="post" action="{{ route('profile.update.email') }}">
                    {{ csrf_field() }}
                    
                    <div class="field">
                        <div class="control has-icons-left">
                            <input type="text" name="email_old" id="inputEmail" class="input" placeholder="Old Email" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="field">
                        <div class="control has-icons-left">
                            <input type="text" name="email" id="inputNewEmail" class="input" placeholder="New Email" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="field">
                        <div class="control has-icons-left">
                            <input type="text" name="email_confirmation" id="inputNewEmailConfirm" class="input" placeholder="New Email Confirm" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                    </div>

                    <button class="button is-primary is-fullwidth" type="submit">Update Email</button>
                </form>
            </div>

            <div class="column">
                <h2>Update Password</h2>
                <form class="form-signin" method="post" action="{{ route('profile.update.email') }}">
                    {{ csrf_field() }}
                    
                    <div class="field">
                        <div class="control has-icons-left">
                            <input type="password" id="inputOldPassword" class="input" placeholder="Old Password" name="password_old" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="field">
                        <div class="control has-icons-left">
                            <input type="password" id="inputNewPassword" class="input" placeholder="New Password" name="password" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="field">
                        <div class="control has-icons-left">
                            <input type="password" id="inputNewPasswordConfirm" class="input" placeholder="New Password Confirm" name="password_confirmation" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                    </div>

                    <button class="button is-primary is-fullwidth" type="submit">Update Password</button>
                </form>
            </div>
        </div>
    </div>
@endsection