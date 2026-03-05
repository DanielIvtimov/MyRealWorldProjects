<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 16px;">
    <h1>You have requested to reset your password</h1>

    @if(!empty($formData['user']->name))
        <p>Hello {{ $formData['user']->name }},</p>
    @endif

    <p>Please click the link below to reset your password:</p>

    <p>
        <a href="{{ route('front.resetPassword', $formData['token']) }}" style="color: #1a73e8;">
            Reset your password
        </a>
    </p>

    <p>Thank you!</p>
</body>
</html>