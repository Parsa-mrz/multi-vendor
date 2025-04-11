<?php

namespace App\Services\Payment;

/**
 * Class PaymentGatewayFactory
 *
 * Factory class responsible for creating instances of different payment gateway classes.
 *
 * @package App\Services\Payment
 */
class PaymentGatewayFactory
{
    /**
     * Creates an instance of a specific payment gateway based on the provided gateway name.
     *
     * @param string $gateway The name of the payment gateway (e.g., 'stripe', 'paypal', 'cod').
     *
     * @return PaymentGatewayInterface The corresponding payment gateway instance.
     *
     * @throws \Exception If the payment gateway is unsupported.
     */
    public static function make(string $gateway): PaymentGatewayInterface
    {
        return match ($gateway) {
            'stripe' => new StripeGateway(),
            'paypal' => new PaypalGateway(),
            'cod'    => new CodGateway(),
            default => throw new \Exception("Unsupported payment gateway: $gateway"),
        };
    }
}
