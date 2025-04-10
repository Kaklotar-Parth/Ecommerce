<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderData;

    /**
     * Create a new message instance.
     */
    public function __construct($orderData)
    {
        $this->orderData = $orderData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Order Confirmation')
            ->view('emails.order_confirmation')
            ->with($this->orderData);
    }
}
