@component('mail::layout')
@slot('header')
<table width="100%">
<tr>
<td style="text-align: center;padding-top: 20px;padding-bottom: 20px">
<img src="{{ asset('/img/beat_saver_logo.png') }}" style="max-width: 250px;display: block;margin: 0 auto;">
</td>
</tr>
</table>
@endslot
<strong>Hello, {{ $user->name }}!</strong>
<p>Welcome to BeatSaver!</p>
<p>Please verify your account by clicking the button below</p>
<table width="100%">
<tr>
<td style="text-align: center;">
<a href="{{ route('register.verify',['token' => $token]) }}" class="button" target="_blank" style="background-color: #ff5722;padding: 10px 35px;font-size: 16px;">Verify Email</a>
</td>
</tr>
</table>
@slot('footer')
@component('mail::footer')
© Copyright {{ date('Y') }} BeatSaver.com
@endcomponent
@endslot
@endcomponent