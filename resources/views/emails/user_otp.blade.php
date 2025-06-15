<!DOCTYPE html>
<html>

<head>
    <title>OTP Verification</title>
</head>

<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h1 style="color: #333;">OTP Verification</h1>

        <p>Hello {{ $user->name }},</p>

        <p>Thank you for registering with us! To complete your registration, please use the following One-Time Password
            (OTP):</p>

        <p
            style="font-size: 24px; font-weight: bold; text-align: center; margin: 30px 0; padding: 15px; background-color: #f0f0f0; border-radius: 8px;">
            Your OTP: {{ $otp }}
        </p>

        <p>This OTP is valid for 2 minutes.</p>

        <p>If you did not request this, please ignore this email.</p>

        <p>Thanks,<br>
            {{ config('app.name') }}</p>

        <p style="font-size: 12px; color: #999; text-align: center; margin-top: 40px;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>

</html>
