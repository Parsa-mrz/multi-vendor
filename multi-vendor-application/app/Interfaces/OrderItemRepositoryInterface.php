<?php

namespace App\Interfaces;

use App\Models\OrderItem;

interface OrderItemRepositoryInterface
{
    public function create(array $data): OrderItem;
    public function getByOrderId(int $orderId): OrderItem;
    public function getByUserId(int $userId): OrderItem;
    public function getByProductId(int $productId): OrderItem;

}
