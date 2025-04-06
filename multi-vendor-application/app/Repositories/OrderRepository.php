<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;

class OrderRepository implements OrderRepositoryInterface
{
    protected $orderItemRepository;

    public function __construct(OrderItemRepository $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }


    public function findById ( int $orderId ): Order
    {
        return Order::find($orderId);
    }


    public function create (array $data): Order
    {
        return Order::create ($data);
    }
}
