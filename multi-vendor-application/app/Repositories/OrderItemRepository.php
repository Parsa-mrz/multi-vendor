<?php

namespace App\Repositories;

use App\Interfaces\OrderItemRepositoryInterface;
use App\Models\OrderItem;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    /**
     * @param  array  $data
     *
     * @return OrderItem
     */
    public function create ( array $data ): OrderItem
    {
        $data['product_id'] = $data['id'];
        return OrderItem::create($data);
    }

    /**
     * @param  int  $orderId
     *
     * @return OrderItem
     */
    public function getByOrderId ( int $orderId ): OrderItem
    {
        return OrderItem::where('orderId', $orderId)->get();
    }

    /**
     * @param  int  $userId
     *
     * @return mixed
     */
    public function getByUserId ( int $userId ): OrderItem
    {
        return OrderItem::where('userId', $userId)->get();
    }

    /**
     * @param  int  $productId
     *
     * @return mixed
     */
    public function getByProductId ( int $productId ): OrderItem
    {
        return OrderItem::where('product_id', $productId)->get();
}}
