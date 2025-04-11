<?php

namespace App\Services\Payment;

/**
 * Interface PaymentGatewayInterface
 *
 * Defines the contract for a payment gateway service.
 *
 * @package App\Services\Payment
 */
interface PaymentGatewayInterface
{
    /**
     * Charges the customer using the provided payment data.
     *
     * @param array $data The data required to charge the customer, e.g., amount, payment method, etc.
     *
     * @return mixed The result of the charge operation, could be a transaction object, success flag, etc.
     */
    public function charge(array $data): mixed;

    /**
     * Retrieves the transaction reference for the most recent transaction.
     *
     * @return string The unique transaction reference.
     */
    public function getTransactionReference(): string;

    /**
     * Determines if the most recent transaction was successful.
     *
     * @return bool True if the transaction was successful, false otherwise.
     */
    public function isSuccessful(): bool;
}
