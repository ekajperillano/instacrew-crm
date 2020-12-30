@component('mail::message')
{{ 'Hi! '. $name }}

We Want you to be part of our CRM.

Click the verify button below to setup your account.

@component('mail::button', ['url' => $url])
    Verify
@endcomponent

<br><br>

Welcome to INSTACREW!

Thanks,<br>
{{ config('app.name') }}
@endcomponent