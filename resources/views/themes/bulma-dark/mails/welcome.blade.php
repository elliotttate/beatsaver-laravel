@component('mail::message')
@component('mail::panel')
**Hello {{ $user->name }},**

Thank you for registering at {{ config('app.name') }}.
Please verify your account by clicking the link below.
@endcomponent

@component('mail::button', ['url' => route('register.verify',['token' => $token])])
{{ route('register.verify',['token' => $token]) }}
@endcomponent
@endcomponent