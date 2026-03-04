<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>New contact message</title>
</head>
<body style="margin: 0; padding: 16px; background-color: #f4f4f4; font-family: Arial, Helvetica, sans-serif; font-size: 16px; line-height: 1.6; color: #333333;">
    <h2 style="margin-top: 0;">You have received a new contact message</h2>

    <p><strong>Name:</strong> {{ $mailData['name'] ?? '-' }}</p>
    <p><strong>Email:</strong> {{ $mailData['email'] ?? '-' }}</p>
    <p><strong>Subject:</strong> {{ $mailData['subject'] ?? '-' }}</p>

    <p><strong>Message:</strong></p>
    <p>{!! nl2br(e($mailData['message'] ?? '')) !!}</p>
</body>
</html>