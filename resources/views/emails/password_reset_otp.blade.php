<!DOCTYPE html>
<html>
<head>
    <title>Password Reset OTP</title>
</head>
<body>
    <h1>Password Reset Request</h1>
    <p>Hello,</p>
    <p>You have requested to reset your password. Use the OTP below to reset your password:</p>

    <h2 style="text-align: center; background-color: #f2f2f2; padding: 10px; border: 1px solid #ccc;">
        {{ $otp }}
    </h2>

    <p>Please note that this OTP is valid for only 10 minutes. If you did not request a password reset, please ignore this email.</p>

    <p>Thank you,</p>
    <p>Your Application Name</p>
</body>
</html>
