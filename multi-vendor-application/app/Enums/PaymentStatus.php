<?php

namespace App\Enums;

/**
 * Enum PaymentStatus
 *
 * Represents the various statuses of a payment.
 */
enum PaymentStatus: string
{
    /**
     * Payment is pending and has not yet been completed.
     */
    case PENDING = 'pending';

    /**
     * Payment has been successfully completed.
     */
    case PAID = 'paid';

    /**
     * Payment has failed.
     */
    case FAILED = 'failed';

    /**
     * Payment has been fully refunded.
     */
    case REFUNDED = 'refunded';

    /**
     * Payment has been partially refunded.
     */
    case PARTIALLY_REFUNDED = 'partially_refunded';

    /**
     * Get a human-readable label for the payment status.
     *
     * @return string The label corresponding to the payment status.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::FAILED => 'Failed',
            self::REFUNDED => 'Refunded',
            self::PARTIALLY_REFUNDED => 'Partially Refunded',
        };
    }
}
