<?php

namespace App\Enums;

/**
 * Enum OrderStatus
 *
 * Represents the various statuses an order can be in.
 */
enum OrderStatus: string
{
    /**
     * The order has been created but not yet processed.
     */
    case PENDING = 'pending';

    /**
     * The order is currently being processed.
     */
    case PROCESSING = 'processing';

    /**
     * The order has been completed.
     */
    case COMPLETED = 'completed';

    /**
     * The order has been cancelled.
     */
    case CANCELLED = 'cancelled';

    /**
     * The order has been refunded.
     */
    case REFUNDED = 'refunded';

    /**
     * Get a human-readable label for the order status.
     *
     * @return string The label corresponding to the order status.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::REFUNDED => 'Refunded',
        };
    }

    /**
     * Get the Tailwind CSS class for displaying the status with color.
     *
     * @return string Tailwind CSS class string based on order status.
     */
    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800',
            self::PROCESSING => 'bg-blue-100 text-blue-800',
            self::COMPLETED => 'bg-green-100 text-green-800',
            self::CANCELLED => 'bg-red-100 text-red-800',
            self::REFUNDED => 'bg-purple-100 text-purple-800',
        };
    }
}
