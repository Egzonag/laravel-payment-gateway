<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Queue\SerializesModels;

class PaymentProcessed
{
    use SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}

class PaymentFailed
{
    use SerializesModels;

    public $order;
    public $message;

    public function __construct(Order $order, $message)
    {
        $this->order = $order;
        $this->message = $message;
    }
}
