<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public array $formData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $formData)
    {
        $this->formData = $formData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->formData['mailSubject'] ?? 'Reset your password';

        // Simple reset URL - adjust when you implement reset screen/route
        $resetUrl = url('/reset-password/'.$this->formData['token']);

        return $this->subject($subject)
                    ->view('email.reset-password')
                    ->with([
                        'formData' => $this->formData,
                        'resetUrl' => $resetUrl,
                    ]);
    }
}
