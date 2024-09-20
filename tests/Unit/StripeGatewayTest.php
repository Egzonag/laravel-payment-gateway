<?php

namespace Tests\Unit\Infrastructure\PaymentGateway;

use App\Payments\StripePaymentGateway;
use PHPUnit\Framework\TestCase;
use App\Models\Order;

class StripeGatewayTest extends TestCase
{
    /** @test */
    public function it_charges_a_card_successfully()
    {
        // Arrange
        $order = new Order(['payment_status' => 'unpaid', 'amount' => 100]);
        $paymentData = ['card_number' => '4242424242424242'];
        $stripeGateway = new StripePaymentGateway();

        // Act
        $response = $stripeGateway->charge($order, $paymentData);

        // Assert
        $this->assertTrue($response->success);
        $this->assertEquals('Payment successful', $response->message);
    }
}
