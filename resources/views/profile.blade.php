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
@endsection