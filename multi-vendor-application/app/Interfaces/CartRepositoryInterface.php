<?php

namespace App\Interfaces;

interface CartRepositoryInterface
{
    public function getCart(): array;
    public function addItem(int $productId, int $quantity): void;
    public function removeItem(int $productId): void;
    public function updateQuantity(int $productId, int $quantity): void;
    public function clear(): void;
}
