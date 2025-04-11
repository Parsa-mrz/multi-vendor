<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Get all products.
     *
     * This method retrieves all products from the database.
     * If no products are found, it returns an empty collection.
     */
    public function getProducts(): ?Collection
    {
        return Product::all();
    }

    /**
     * Get paginated products.
     *
     * This method retrieves products with pagination support.
     * The number of products per page can be specified via the $perPage parameter.
     */
    public function getProductsPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Product::paginate($perPage);
    }

    /**
     * Get a single product by its ID.
     *
     * This method retrieves a single product by its ID.
     * If the product is not found, it returns null.
     */
    public function getProduct(int $id): ?Product
    {
        return Product::find($id);
    }

    /**
     * Get products by vendor ID.
     *
     * This method retrieves all products associated with a specific vendor.
     */
    public function findByVendorId(int $vendorId): Collection
    {
        return Product::where('vendor_id', $vendorId)->get();
    }

    /**
     * Get products by category ID.
     *
     * This method retrieves all products associated with a specific category.
     */
    public function findByCategoryId(int $categoryId): Collection
    {
        return Product::where('category_id', $categoryId)->get();
    }

    /**
     * Get a product by its slug.
     *
     * This method retrieves a product using its slug.
     * If no product is found with the given slug, it returns null.
     */
    public function getProductBySlug(string $slug): ?Product
    {
        return Product::where('slug', $slug)->first();
    }

    /**
     * Update an existing product.
     *
     * This method updates the product with the given ID using the provided data.
     * It returns the updated product instance.
     */
    public function update(int $productId, array $data): Product
    {
        $product = $this->getProduct($productId);
        $product->update($data);

        return $product;
    }
}
