<x-mail::message>
# Forgot Password
Dear {{ $studentNum}} <br>
We received a request to reset a password for your account. To reset your password, kindly click on the button below
to proceed to the reset password page. After reseting your password, please login to your account.

<x-mail::button :url="$url">
Change Password
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
