<?php

namespace App\Repositories;

use App\Interfaces\CartRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Session;
use Exception;
use function dd;

class CartRepository implements CartRepositoryInterface
{
    protected $productRepository;
    protected const CART_KEY = 'cart';

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get the current cart contents
     * @return array
     */
    public function getCart(): array
    {
        return Session::get(self::CART_KEY, []);
    }

    /**
     * Add an item to the cart
     * @param int $productId
     * @param int $quantity
     * @throws Exception
     * @return void
     */
    public function addItem(int $productId, int $quantity): void
    {
        $cart = $this->getCart();
        $product = $this->productRepository->getProduct($productId);

        if (!$product) {
            throw new Exception(
                "Product not found",
                404
            );
        }

        if ($quantity <= 0) {
            throw new Exception("Quantity must be greater than 0", 422);
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'discount' => $product->discount,
                'quantity' => $quantity,
                'slug' => $product->slug
            ];
        }

        if ($product->quantity < $cart[$productId]['quantity']) {
            throw new Exception(
                "Insufficient stock for {$product->name}",
                422
            );
        }

        Session::put(self::CART_KEY, $cart);
    }

    /**
     * Remove an item from the cart
     * @param int $productId
     * @return void
     */
    public function removeItem(int $productId): void
    {
        $cart = $this->getCart();

        if (!isset($cart[$productId])) {
            throw new Exception("Product not found in cart", 404);
        }

        unset($cart[$productId]);
        Session::put(self::CART_KEY, $cart);
    }

    /**
     * Update item quantity in the cart
     * @param int $productId
     * @param int $quantity
     * @throws Exception
     * @return void
     */
    public function updateQuantity(int $productId, int $quantity): void
    {
        $cart = $this->getCart();
        $product = $this->productRepository->getProduct($productId);

        if (!$product) {
            throw new Exception(
                "Product not found",
                404
            );
        }

        if ($quantity <= 0) {
            throw new Exception("Quantity must be greater than 0", 422);
        }

        if (!isset($cart[$productId])) {
            throw new Exception("Product with ID {$productId} not found in cart", 404);
        }

        if ($product->quantity < $quantity) {
            throw new Exception("Insufficient stock for {$product->name}", 422);
        }

        $cart[$productId]['quantity'] = $quantity;
        Session::put(self::CART_KEY, $cart);
    }

    /**
     * Clear the entire cart
     * @return void
     */
    public function clear(): void
    {
        Session::forget(self::CART_KEY);
    }
}
