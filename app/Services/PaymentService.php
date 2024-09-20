<?php

namespace App\Services;

use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Exceptions\PaymentException;
use App\Payments\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use App\Events\PaymentProcessed;
use App\Events\PaymentFailed;

class PaymentService
{
    protected $paymentRepository;
    protected $paymentGateway;

    public function __construct(PaymentRepositoryInterface $paymentRepository, PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentRepository = $paymentRepository;
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * @param int $orderId
     * @param array $paymentData
     * @return bool
     * @throws PaymentException
     */
    public function processPayment(int $orderId, array $paymentData): bool
    {
        try { 
            $order = $this->paymentRepository->getOrderById($orderId);

            if (!$order) {
                throw new PaymentException("Order not found for ID: $orderId");
            }
 
            if ($order->isPaid()) {
                throw new PaymentException("Order already paid");
            }
 
            $paymentResponse = $this->paymentGateway->charge($order, $paymentData);

            if (!$paymentResponse->success) { 
                Log::error('Payment failed', ['orderId' => $orderId, 'message' => $paymentResponse->message]);
                Event::dispatch(new PaymentFailed($order, $paymentResponse->message));

                throw new PaymentException('Payment failed: ' . $paymentResponse->message);
            }

            Log::info('Payment successful', ['orderId' => $orderId]);
            $this->paymentRepository->updatePaymentStatus($orderId, 'paid');

            $this->paymentRepository->recordPaymentHistory($orderId, $paymentData);

            Event::dispatch(new PaymentProcessed($order));

            return true;

        } catch (\Exception $e) {
            Log::error('Payment processing failed', ['orderId' => $orderId, 'error' => $e->getMessage()]);
            throw new PaymentException('Payment processing failed.');
        }
    }

    /**
     * @param int $orderId
     * @param array $refundData
     * @return bool
     * @throws PaymentException
     */
    public function refundPayment(int $orderId, array $refundData): bool
    {
        try {
            $order = $this->paymentRepository->getOrderById($orderId);

            if (!$order || !$order->isPaid()) {
                throw new PaymentException("Cannot refund unpaid or non-existent order.");
            }

            $refundResponse = $this->paymentGateway->refund($order, $refundData);

            if (!$refundResponse->success) {
                throw new PaymentException('Refund failed: ' . $refundResponse->message);
            }

            Log::info('Refund successful', ['orderId' => $orderId]);
            $this->paymentRepository->updatePaymentStatus($orderId, 'refunded');

            return true;

        } catch (\Exception $e) {
            Log::error('Refund failed', ['orderId' => $orderId, 'error' => $e->getMessage()]);
            throw new PaymentException('Refund processing failed.');
        }
    }
}
