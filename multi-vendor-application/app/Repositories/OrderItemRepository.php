<?php

namespace App\Repositories;

use App\Interfaces\OrderItemRepositoryInterface;
use App\Models\OrderItem;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    /**
     * Create a new order item.
     *
     * This method creates a new order item in the database with the given data.
     * It modifies the provided data to ensure the 'product_id' is correctly set.
     */
    public function create(array $data): OrderItem
    {
        $data['product_id'] = $data['id'];

        return OrderItem::create($data);
    }

    /**
     * Get order items by the order ID.
     *
     * This method retrieves all order items associated with a specific order ID.
     */
    public function getByOrderId(int $orderId): OrderItem
    {
        return OrderItem::where('orderId', $orderId)->get();
    }

    /**
     * Get order items by the user ID.
     *
     * This method retrieves all order items associated with a specific user ID.
     */
    public function getByUserId(int $userId): OrderItem
    {
        return OrderItem::where('userId', $userId)->get();
    }

    /**
     * Get order items by the product ID.
     *
     * This method retrieves all order items associated with a specific product ID.
     */
    public function getByProductId(int $productId): OrderItem
    {
        return OrderItem::where('product_id', $productId)->get();
    }
}
