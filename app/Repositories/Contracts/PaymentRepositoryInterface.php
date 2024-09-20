<?php

namespace App\Repositories\Contracts;

interface PaymentRepositoryInterface
{
    public function getOrderById(int $id);

    public function updatePaymentStatus(int $orderId, string $status);

    public function recordPaymentHistory(int $orderId, array $paymentData);

    public function refundPaymentHistory(int $orderId, array $refundData);
}
