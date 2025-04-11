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

    protected $cartService;

    /**
     * Create a new class instance.
     */
    public function __construct(OrderRepository $orderRepository, OrderItemRepository $orderItemRepository, ProductRepository $productRepository, CartService $cartService)
    {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }

    public function createOrder(array $data): Order
    {
        $order = $this->orderRepository->create($data['order']);

        foreach ($data['items'] as $item) {
            $item['order_id'] = $order->id;
            $this->reduceProductQuantity($item['id'], $item['quantity']);
            $this->orderItemRepository->create($item);
        }

        $this->cartService->clearCart();

        return $order;
    }

    private function reduceProductQuantity(int $productId, int $quantity)
    {
        $product = $this->productRepository->getProduct($productId);
        $this->productRepository->update($productId, ['quantity' => $product->quantity - $quantity]);
    }
}
