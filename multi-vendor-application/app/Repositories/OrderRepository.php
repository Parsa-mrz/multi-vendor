<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @var OrderItemRepository
     */
    protected $orderItemRepository;

    /**
     * OrderRepository constructor.
     *
     * Inject the OrderItemRepository dependency.
     */
    public function __construct(OrderItemRepository $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }

    /**
     * Find an order by its ID.
     *
     * This method retrieves an order by its ID. If the order is not found, it will return null.
     *
     * @return Order|null
     */
    public function findById(int $orderId): Order
    {
        return Order::find($orderId);
    }

    /**
     * Create a new order.
     *
     * This method creates a new order in the database with the given data.
     */
    public function create(array $data): Order
    {
        return Order::create($data);
    }
}
