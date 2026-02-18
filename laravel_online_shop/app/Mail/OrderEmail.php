<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = isset($this->mailData['subject']) 
            ? $this->mailData['subject'] 
            : 'Order Confirmation - Order #' . $this->mailData['order']->id;
        
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');
        
        // If from address is null or empty, use a default
        if(empty($fromAddress) || $fromAddress === 'null' || $fromAddress === 'hello@example.com'){
            $fromAddress = 'noreply@laravelshop.com';
        }
        
        if(empty($fromName) || $fromName === 'Example'){
            $fromName = config('app.name', 'Laravel Shop');
        }
            
        return $this->view('email.order')
                    ->from($fromAddress, $fromName)
                    ->subject($subject);
    }
}
