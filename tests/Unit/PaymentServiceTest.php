<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\EloquentPaymentRepository;
use App\Models\Order;
use App\Models\PaymentHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;


class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $paymentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentRepository = new EloquentPaymentRepository();
    }

    public function testGetOrderById()
    {
        $order = Order::factory()->create();

        $result = $this->paymentRepository->getOrderById($order->id);

        $this->assertEquals($order->id, $result->id);
    }

    public function testUpdatePaymentStatus()
    {
        $order = Order::factory()->create(['payment_status' => 'pending']);

        $this->paymentRepository->updatePaymentStatus($order->id, 'completed');

        $updatedOrder = $this->paymentRepository->getOrderById($order->id);
        $this->assertEquals('completed', $updatedOrder->payment_status);
    }

    public function testRecordPaymentHistory()
    {
        $order = Order::factory()->create();
        $paymentData = [
            'amount' => 100,
            'gateway' => 'credit_card',
        ];

        $this->paymentRepository->recordPaymentHistory($order->id, $paymentData);

        $paymentHistory = PaymentHistory::first();
        $this->assertEquals($order->id, $paymentHistory->order_id);
        $this->assertEquals($paymentData['amount'], $paymentHistory->amount);
        $this->assertEquals('success', $paymentHistory->status);
        $this->assertEquals($paymentData['gateway'], $paymentHistory->payment_gateway);
    }

    public function testRefundPaymentHistory()
    {
        $order = Order::factory()->create();
        $refundData = [
            'amount' => 50,
            'gateway' => 'credit_card',
        ];

        $this->paymentRepository->refundPaymentHistory($order->id, $refundData);

        $refundHistory = PaymentHistory::latest()->first();
        $this->assertEquals($order->id, $refundHistory->order_id);
        $this->assertEquals($refundData['amount'], $refundHistory->amount);
        $this->assertEquals('refunded', $refundHistory->status);
        $this->assertEquals($refundData['gateway'], $refundHistory->payment_gateway);
    }
}
