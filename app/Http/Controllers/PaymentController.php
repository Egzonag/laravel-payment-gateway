<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use App\Exceptions\PaymentException;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle payment for an order.
     *
     * @param Request $request
     * @param int $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request, int $orderId)
    {
        try {
            $paymentData = $request->only(['card_number', 'amount']);
            $this->paymentService->processPayment($orderId, $paymentData);

            return response()->json(['message' => 'Payment successful'], 200);

        } catch (PaymentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Handle refund for an order.
     *
     * @param Request $request
     * @param int $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function refundPayment(Request $request, int $orderId)
    {
        try {
            $refundData = $request->only(['amount']);
            $this->paymentService->refundPayment($orderId, $refundData);

            return response()->json(['message' => 'Refund successful'], 200);

        } catch (PaymentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
