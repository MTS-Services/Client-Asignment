<x-mail::message>
    Hello {{ $user->name }},

    Thank you for registering with us! To complete your registration, please use the following One-Time Password (OTP):

    **Your OTP: {{ $otp }}**

    This OTP is valid for 2 minutes.

    If you did not request this, please ignore this email.

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
