<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Logged In</title>
</head>

<body>
<div class="container" style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
    <h1 style="color: #333;">Verify Your Email, {{ $email }}</h1>
    <p style="color: #666; line-height: 1.5;">
        Thank you for using our service! To complete your email verification, please use the following One-Time Password (OTP):
    </p>
    <div style="text-align: center; margin: 20px 0;">
        <h2 style="background: #f5f5f5; display: inline-block; padding: 10px 20px; border-radius: 5px; color: #333;">
            {{ $otp }}
        </h2>
    </div>
    <p style="color: #666; line-height: 1.5;">
        This OTP is valid for <strong>5 minutes</strong>. Please enter it in the verification form to confirm your email address.
    </p>
    <div class="footer" style="margin-top: 20px; color: #999; font-size: 12px;">
        If you didn’t request this verification, please ignore this email or contact support to secure your account.
    </div>
</div>
</body>

</html>
