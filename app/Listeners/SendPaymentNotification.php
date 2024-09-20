<?php

namespace App\Listeners;

use App\Events\PaymentProcessed;
use App\Events\PaymentFailed;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentSuccessMail;
use App\Mail\PaymentFailureMail;

class SendPaymentNotification
{
    public function handlePaymentProcessed(PaymentProcessed $event)
    {
        Mail::to($event->order->user->email)
            ->send(new PaymentSuccessMail($event->order));
    }

    public function handlePaymentFailed(PaymentFailed $event)
    {
        Mail::to($event->order->user->email)
            ->send(new PaymentFailureMail($event->order, $event->message));
    }
}
