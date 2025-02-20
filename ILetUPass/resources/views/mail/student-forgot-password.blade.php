<x-mail::message>
    <div style="text-align: center;">
        <img src="{{ asset('/public/images/LogoPNG.png') }}" width="300" height="200">
    </div>

    <div style="font-weight:bold;">Dear {{ $studentNum }}</div><br>

    We received a request to reset a password for your account. To reset your password, kindly click on the button below
    to proceed to the reset password page. After reseting your password, please login to your account,

    @component('mail::button', ['url' => route('student-change-pass', ['email' => $email]), 'color' => 'red'])
        Change Password
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
