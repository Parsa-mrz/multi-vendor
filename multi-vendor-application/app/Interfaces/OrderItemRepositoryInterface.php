<?php

namespace App\Interfaces;

use App\Models\OrderItem;

/**
 * Interface OrderItemRepositoryInterface
 *
 * Defines the contract for managing order items within the application.
 * The interface includes methods for creating order items and retrieving
 * them by order ID, user ID, or product ID.
 */
interface OrderItemRepositoryInterface
{
    /**
     * Create a new order item.
     *
     * @param  array  $data  The data required to create a new order item (e.g., order_id, product_id, quantity, etc.).
     * @return \App\Models\OrderItem The created order item instance.
     */
    public function create(array $data): OrderItem;

    /**
     * Get an order item by its associated order ID.
     *
     * @param  int  $orderId  The ID of the order for which to retrieve the order item.
     * @return \App\Models\OrderItem The order item associated with the given order ID.
     */
    public function getByOrderId(int $orderId): OrderItem;

    /**
     * Get an order item by its associated user ID.
     *
     * @param  int  $userId  The ID of the user for which to retrieve the order item.
     * @return \App\Models\OrderItem The order item associated with the given user ID.
     */
    public function getByUserId(int $userId): OrderItem;

    /**
     * Get an order item by its associated product ID.
     *
     * @param  int  $productId  The ID of the product for which to retrieve the order item.
     * @return \App\Models\OrderItem The order item associated with the given product ID.
     */
    public function getByProductId(int $productId): OrderItem;
}
