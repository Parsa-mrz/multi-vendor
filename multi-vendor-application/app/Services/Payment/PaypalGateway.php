<?php

namespace App\Services\Payment;

/**
 * Class PaypalGateway
 *
 * Represents the PayPal payment gateway.
 * Implements the PaymentGatewayInterface to provide functionality for charging,
 * retrieving a transaction reference, and checking the transaction status.
 *
 * @package App\Services\Payment
 */
class PaypalGateway implements PaymentGatewayInterface
{
    /**
     * @var bool The status of the transaction.
     */
    protected $transactionStatus;

    /**
     * Charges the customer using the provided payment data (simulated for PayPal).
     *
     * @param array $data The payment data (e.g., customer information, amount, etc.).
     *
     * @return mixed The current instance of the PaypalGateway (for method chaining).
     */
    public function charge(array $data): mixed
    {
        // Simulate PayPal transaction (failed in this case)
        $this->transactionStatus = false;
        return $this;
    }

    /**
     * Retrieves the transaction reference for the PayPal transaction.
     *
     * @return string A unique transaction reference, prefixed with "paypal_".
     */
    public function getTransactionReference(): string
    {
        return 'paypal_' . uniqid('txn_', true);
    }

    /**
     * Determines if the PayPal transaction was successful.
     *
     * @return bool True if the transaction was successful, false otherwise.
     */
    public function isSuccessful(): bool
    {
        return $this->transactionStatus === true;
    }
}
