<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use function is_null;

class ProductService
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * ProductService constructor.
     *
     * Inject the ProductRepository dependency.
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get products with optional pagination.
     *
     * This method retrieves all products, or retrieves them paginated if the `$perPage` argument is provided.
     *
     * @param  int|null  $perPage
     */
    public function getProducts($perPage = null): Collection|LengthAwarePaginator
    {
        if (! is_null($perPage)) {
            return $this->productRepository->getProductsPaginated($perPage);
        }

        return $this->productRepository->getProducts();
    }

    /**
     * Get a product or products by a specific attribute.
     *
     * This method retrieves a product based on a given attribute and value.
     * Supported attributes are 'id', 'vendor_id', 'slug', and 'category_id'.
     * If no matching attribute is found, it defaults to retrieving by 'id'.
     *
     * @param  string  $attribute  The attribute to search by (e.g., 'id', 'vendor_id', 'slug', 'category_id').
     * @param  string|int  $value  The value of the attribute to search for.
     * @return Product|Collection
     */
    public function getProductByAttribute(string $attribute, string|int $value): Product
    {
        return match ($attribute) {
            'id' => $this->productRepository->getProduct($value),
            'vendor_id' => $this->productRepository->findByVendorId($value),
            'slug' => $this->productRepository->getProductBySlug($value),
            'category_id' => $this->productRepository->findByCategoryId($value),
            default => $this->productRepository->getProduct($value),
        };
    }
}
