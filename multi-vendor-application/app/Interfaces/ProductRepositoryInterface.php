<?php

namespace App\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface ProductRepositoryInterface
 *
 * Defines the contract for managing products within the application.
 * The interface includes methods for retrieving products, finding products by
 * vendor or category, and updating product details.
 */
interface ProductRepositoryInterface
{
    /**
     * Get all products.
     *
     * @return \Illuminate\Database\Eloquent\Collection|null A collection of all products, or null if no products exist.
     */
    public function getProducts(): ?Collection;

    /**
     * Get products with pagination.
     *
     * @param  int  $perPage  The number of products per page.
     * @return \Illuminate\Pagination\LengthAwarePaginator A paginator instance containing the products.
     */
    public function getProductsPaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get a specific product by its ID.
     *
     * @param  int  $id  The ID of the product to retrieve.
     * @return \App\Models\Product|null The product if found, or null if not found.
     */
    public function getProduct(int $id): ?Product;

    /**
     * Find products by a specific vendor ID.
     *
     * @param  int  $vendorId  The ID of the vendor whose products to retrieve.
     * @return \Illuminate\Database\Eloquent\Collection|null A collection of products for the given vendor, or null if no products are found.
     */
    public function findByVendorId(int $vendorId): ?Collection;

    /**
     * Find products by a specific category ID.
     *
     * @param  int  $categoryId  The ID of the category whose products to retrieve.
     * @return \Illuminate\Database\Eloquent\Collection|null A collection of products for the given category, or null if no products are found.
     */
    public function findByCategoryId(int $categoryId): ?Collection;

    /**
     * Get a specific product by its slug.
     *
     * @param  string  $slug  The slug of the product to retrieve.
     * @return \App\Models\Product|null The product if found, or null if not found.
     */
    public function getProductBySlug(string $slug): ?Product;

    /**
     * Update a product's details.
     *
     * @param  int  $productId  The ID of the product to update.
     * @param  array  $data  The data to update the product with (e.g., name, price, description, etc.).
     * @return \App\Models\Product The updated product instance.
     */
    public function update(int $productId, array $data): Product;
}
