@component('mail::message')
@component('mail::panel')
**Hello {{ $user->name }},**

You recently changed your email for {{ config('app.name') }}.
In order to continue uploading song we need you to verify your new email by clicking the link below.
@endcomponent

@component('mail::button', ['url' => route('register.verify',['token' => $token])])
{{ route('register.verify',['token' => $token]) }}
@endcomponent
@endcomponent