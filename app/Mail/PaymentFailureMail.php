<?php

namespace App\Mail;

use Illuminate\Bus\Queueable; 
use Illuminate\Mail\Mailable; 
use Illuminate\Queue\SerializesModels;

class PaymentFailureMail extends Mailable
{
    use Queueable, SerializesModels;
    public $order;


    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->view('emails.payment_failure')
            ->with(['order' => $this->order]);
    }
}
