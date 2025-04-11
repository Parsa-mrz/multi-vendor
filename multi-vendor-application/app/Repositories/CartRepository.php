<?php

namespace App\Repositories;

use App\Interfaces\CartRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Session;

class CartRepository implements CartRepositoryInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * The session key for the cart
     */
    protected const CART_KEY = 'cart';

    /**
     * CartRepository constructor.
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get the current cart contents.
     *
     * Retrieves the current cart from the session. If the cart is empty,
     * it will return an empty array.
     */
    public function getCart(): array
    {
        return Session::get(self::CART_KEY, []);
    }

    /**
     * Add an item to the cart.
     *
     * Adds a product to the cart by the specified product ID and quantity.
     * If the product doesn't exist or the quantity is invalid, an exception will be thrown.
     * If the product is already in the cart, its quantity will be updated.
     *
     *
     * @throws Exception If the product is not found, quantity is invalid, or insufficient stock.
     */
    public function addItem(int $productId, int $quantity): void
    {
        $cart = $this->getCart();
        $product = $this->productRepository->getProduct($productId);

        if (! $product) {
            throw new Exception(
                'Product not found',
                404
            );
        }

        if ($quantity <= 0) {
            throw new Exception('Quantity must be greater than 0', 422);
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
                'slug' => $product->slug,
                'image' => $product->image,
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
     * Remove an item from the cart.
     *
     * Removes the product with the specified product ID from the cart.
     * If the product is not found in the cart, an exception will be thrown.
     *
     *
     * @throws Exception If the product is not found in the cart.
     */
    public function removeItem(int $productId): void
    {
        $cart = $this->getCart();

        if (! isset($cart[$productId])) {
            throw new Exception('Product not found in cart', 404);
        }

        unset($cart[$productId]);
        Session::put(self::CART_KEY, $cart);
    }

    /**
     * Update item quantity in the cart.
     *
     * Updates the quantity of the specified product in the cart.
     * If the product doesn't exist or the quantity is invalid, an exception will be thrown.
     * If the stock is insufficient, an exception will also be thrown.
     *
     *
     * @throws Exception If the product is not found, quantity is invalid, or insufficient stock.
     */
    public function updateQuantity(int $productId, int $quantity): void
    {
        $cart = $this->getCart();
        $product = $this->productRepository->getProduct($productId);

        if (! $product) {
            throw new Exception(
                'Product not found',
                404
            );
        }

        if ($quantity <= 0) {
            throw new Exception('Quantity must be greater than 0', 422);
        }

        if (! isset($cart[$productId])) {
            throw new Exception("Product with ID {$productId} not found in cart", 404);
        }

        if ($product->quantity < $quantity) {
            throw new Exception("Insufficient stock for {$product->name}", 422);
        }

        $cart[$productId]['quantity'] = $quantity;
        Session::put(self::CART_KEY, $cart);
    }

    /**
     * Clear the entire cart.
     *
     * Removes all items from the cart and clears the session storage.
     */
    public function clear(): void
    {
        Session::forget(self::CART_KEY);
    }
}
