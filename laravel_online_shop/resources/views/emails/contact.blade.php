<h2>New contact message</h2>

<p><strong>Name:</strong> {{ $mailData['name'] ?? '-' }}</p>
<p><strong>Email:</strong> {{ $mailData['email'] ?? '-' }}</p>
<p><strong>Subject:</strong> {{ $mailData['subject'] ?? '-' }}</p>

<p><strong>Message:</strong></p>
<p>{!! nl2br(e($mailData['message'] ?? '')) !!}</p>

