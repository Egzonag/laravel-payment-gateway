<?php

namespace App\Payments;

use App\Models\Order;

class StripePaymentGateway implements PaymentGatewayInterface
{
    public function charge(Order $order, array $paymentData)
    {
        // Fake Stripe API call for demo
        if ($paymentData['card_number'] === '4242424242424242') {
            return (object)[
                'success' => true,
                'message' => 'Payment successful',
            ];
        }

        return (object)[
            'success' => false,
            'message' => 'Payment declined',
        ];
    }

    public function refund(Order $order, array $refundData)
    {
        // Fake Stripe refund API call for demo
        if ($order->payment_status === 'paid') {
            return (object)[
                'success' => true,
                'message' => 'Refund successful',
            ];
        }

        return (object)[
            'success' => false,
            'message' => 'Refund failed',
        ];
    }
}
