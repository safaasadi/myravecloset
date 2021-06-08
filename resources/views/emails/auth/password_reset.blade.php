@component('mail::message')
# {{ $user->username }},

Sorry you lost your password. We've got you covered! Just click the button below to reset your password.

@component('mail::button', ['url' => 'https://myravecloset.com/change-password?token=' . $reset_token])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
