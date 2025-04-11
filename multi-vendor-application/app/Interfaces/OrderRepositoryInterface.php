<?php

namespace App\Interfaces;

use App\Models\Order;

/**
 * Interface OrderRepositoryInterface
 *
 * Defines the contract for managing orders within the application.
 * The interface includes methods for retrieving orders by ID and creating new orders.
 */
interface OrderRepositoryInterface
{
    /**
     * Find an order by its ID.
     *
     * @param  int  $orderId  The ID of the order to retrieve.
     * @return \App\Models\Order The order instance associated with the given ID.
     */
    public function findById(int $orderId): Order;

    /**
     * Create a new order.
     *
     * @param  array  $data  The data required to create a new order (e.g., user_id, product details, status, etc.).
     * @return \App\Models\Order The created order instance.
     */
    public function create(array $data): Order;
}
