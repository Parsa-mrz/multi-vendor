<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use Exception;

class CartService
{
    protected $cartRepository;

    protected $productRepository;

    public function __construct(
        CartRepository $cartRepository,
        ProductRepository $productRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Cast the necessary fields to float and int
     */
    private function castItemFields(array $item): array
    {
        $item['price'] = (float) $item['price'];
        $item['sale_price'] = $item['sale_price'] ? (float) $item['sale_price'] : null;
        $item['discount'] = (float) $item['discount'];
        $item['quantity'] = (int) $item['quantity'];

        return $item;
    }

    /**
     * Get all items in the cart
     */
    public function getCartItems(): array
    {
        $items = $this->cartRepository->getCart();

        return array_map([$this, 'castItemFields'], $items);
    }

    /**
     * Add product to cart
     *
     * @throws Exception
     */
    public function addToCart(int $productId, int $quantity = 1): void
    {
        if ($quantity <= 0) {
            throw new Exception('Quantity must be greater than 0', 422);
        }

        $this->cartRepository->addItem($productId, $quantity);
    }

    /**
     * Remove product from cart
     */
    public function removeFromCart(int $productId): void
    {
        $this->cartRepository->removeItem($productId);
    }

    /**
     * Update product quantity in cart
     *
     * @throws Exception
     */
    public function updateCartQuantity(int $productId, int $quantity): void
    {
        $this->cartRepository->updateQuantity($productId, $quantity);
    }

    /**
     * Calculate cart total
     */
    public function getTotal(): float
    {
        $items = $this->cartRepository->getCart();
        $items = array_map([$this, 'castItemFields'], $items);
        $total = collect($items)->sum(function ($item) {
            $price = $item['sale_price'] ?? $item['price'];

            return $price * $item['quantity'];
        });

        return $total;
    }

    /**
     * Clear all items from cart
     */
    public function clearCart(): void
    {
        $this->cartRepository->clear();
    }

    /**
     * Get product details
     *
     * @return mixed
     */
    public function getProduct(int $productId)
    {
        return $this->productRepository->getProduct($productId);
    }

    /**
     * Get cart item count
     */
    public function getItemCount(): int
    {
        $items = $this->cartRepository->getCart();

        return collect($items)->sum('quantity');
    }
}
