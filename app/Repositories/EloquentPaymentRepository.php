<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\PaymentHistory;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class EloquentPaymentRepository implements PaymentRepositoryInterface
{
    public function getOrderById(int $id)
    {
        return Order::find($id);
    }

    public function updatePaymentStatus(int $orderId, string $status)
    {
        $order = Order::find($orderId);
        $order->payment_status = $status;
        $order->save();
    }

    public function recordPaymentHistory(int $orderId, array $paymentData)
    {
        PaymentHistory::create([
            'order_id' => $orderId,
            'amount' => $paymentData['amount'],
            'status' => 'success',
            'payment_gateway' => $paymentData['gateway'],
            'created_at' => now(),
        ]);
    }

    public function refundPaymentHistory(int $orderId, array $refundData)
    {
        PaymentHistory::create([
            'order_id' => $orderId,
            'amount' => $refundData['amount'],
            'status' => 'refunded',
            'payment_gateway' => $refundData['gateway'],
            'created_at' => now(),
        ]);
    }
}
