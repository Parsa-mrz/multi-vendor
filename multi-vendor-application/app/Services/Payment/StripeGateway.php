<?php

namespace App\Services\Payment;

/**
 * Class StripeGateway
 *
 * Represents the Stripe payment gateway.
 * Implements the PaymentGatewayInterface to provide functionality for charging,
 * retrieving a transaction reference, and checking the transaction status.
 *
 * @package App\Services\Payment
 */
class StripeGateway implements PaymentGatewayInterface
{
    /**
     * @var bool The status of the transaction.
     */
    protected $transactionStatus;

    /**
     * Charges the customer using the provided payment data (simulated for Stripe).
     *
     * @param array $data The payment data (e.g., customer information, amount, etc.).
     *
     * @return mixed The current instance of the StripeGateway (for method chaining).
     */
    public function charge(array $data): mixed
    {
        // Simulate successful Stripe transaction
        $this->transactionStatus = true;
        return $this;
    }

    /**
     * Retrieves the transaction reference for the Stripe transaction.
     *
     * @return string A unique transaction reference, prefixed with "stripe_".
     */
    public function getTransactionReference(): string
    {
        return 'stripe_' . uniqid('txn_', true);
    }

    /**
     * Determines if the Stripe transaction was successful.
     *
     * @return bool True if the transaction was successful, false otherwise.
     */
    public function isSuccessful(): bool
    {
        return $this->transactionStatus === true;
    }
}
