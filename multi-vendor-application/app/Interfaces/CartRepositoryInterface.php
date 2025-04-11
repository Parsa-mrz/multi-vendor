<?php

namespace App\Interfaces;

/**
 * Interface CartRepositoryInterface
 *
 * Defines the contract for the CartRepository to manage cart items.
 * The interface includes methods for retrieving the cart, adding items,
 * removing items, updating quantities, and clearing the cart.
 */
interface CartRepositoryInterface
{
    /**
     * Get the current cart items.
     *
     * @return array An array of cart items, typically in the form of product details and quantities.
     */
    public function getCart(): array;

    /**
     * Add a product to the cart.
     *
     * @param  int  $productId  The ID of the product to add.
     * @param  int  $quantity  The quantity of the product to add.
     */
    public function addItem(int $productId, int $quantity): void;

    /**
     * Remove a product from the cart.
     *
     * @param  int  $productId  The ID of the product to remove.
     */
    public function removeItem(int $productId): void;

    /**
     * Update the quantity of a product in the cart.
     *
     * @param  int  $productId  The ID of the product to update.
     * @param  int  $quantity  The new quantity of the product.
     */
    public function updateQuantity(int $productId, int $quantity): void;

    /**
     * Clear all items from the cart.
     */
    public function clear(): void;
}
