<?php

namespace App\Interfaces;

use App\Models\Order;
use App\Models\OrderItem;

interface OrderRepositoryInterface
{
    public function findById(int $orderId): Order;
    public function create(array$data):Order;
}
