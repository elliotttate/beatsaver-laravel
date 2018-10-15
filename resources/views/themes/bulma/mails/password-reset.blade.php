@component('mail::message')
@component('mail::panel')
**Hello {{ $user->name }},**

You or someone just requested a password reset.
In order to complete the request click the link below.
@endcomponent

@component('mail::button', ['url' => route('password.reset.complete.form',['token' => $token])])
{{ route('password.reset.complete.form',['token' => $token]) }}
@endcomponent
If you didn't request a password reset ignore this mail.
@endcomponent