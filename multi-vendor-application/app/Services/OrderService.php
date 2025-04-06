<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;

class OrderService
{
    protected $orderRepository;
    protected $orderItemRepository;
    protected $productRepository;

    /**
     * Create a new class instance.
     */
    public function __construct ( OrderRepository $orderRepository, OrderItemRepository $orderItemRepository,ProductRepository $productRepository )
    {
        $this->orderRepository     = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->productRepository   = $productRepository;
    }

    public function createOrder ( array $data ): Order
    {
        $order = $this->orderRepository->create ( $data['order'] );

        foreach ( $data[ 'items' ] as $item ) {
            $item['order_id'] = $order->id;
            $this->reduceProductQuantity ( $item['id'], $item['quantity'] );
            $this->orderItemRepository->create ( $item );
        }

        return $order;
    }

    private function reduceProductQuantity ( int $productId, int $quantity )
    {
        $this->productRepository->update ($productId,['quantity' => $quantity]);
    }
}
