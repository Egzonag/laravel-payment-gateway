<?php

namespace App\Payments;

use App\Models\Order;

interface PaymentGatewayInterface
{
    public function charge(Order $order, array $paymentData);

    public function refund(Order $order, array $refundData);
}
