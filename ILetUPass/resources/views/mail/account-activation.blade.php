<x-mail::message>
# Activation Account

Hi {{ $email }} <br>
We're thrilled to have you as a member of I Let U Pass. It's time to activate your account and set up your
personalized password. Simply click the button below to complete your account activation and set a new password.

<x-mail::button :url="$url" >
Password Setup
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
