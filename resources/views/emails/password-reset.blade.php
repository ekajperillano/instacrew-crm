@component('mail::message')
#{{ 'Hi! '. $name }}

We got a request to reset your CRM Password;

@component('mail::button', ['url' => $url, 'color' =>'success'])
Reset Password
@endcomponent


If you ignore this message, your password won't be changed
This password request will expire in 6 days.

<br><br>

If you didn't request for a password reset, Please let your developer know.

Thanks,<br>
{{ config('app.name') }}
@endcomponent