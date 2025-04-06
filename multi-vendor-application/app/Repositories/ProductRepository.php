<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Get all products.
     *
     * @return Collection
     */
    public function getProducts(): ?Collection
    {
        return Product::all();
    }

    public function getProductsPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Product::paginate($perPage);
    }

    /**
     * Get a single product by its ID.
     *
     * @param  int  $id
     * @return Product|null
     */
    public function getProduct(int $id): ?Product
    {
        return Product::find($id);
    }

    /**
     * Get products by vendor ID.
     *
     * @param  int  $vendorId
     * @return Collection
     */
    public function findByVendorId(int $vendorId): Collection
    {
        return Product::where('vendor_id', $vendorId)->get();
    }

    /**
     * Get products by category ID.
     *
     * @param  int  $categoryId
     * @return Collection
     */
    public function findByCategoryId(int $categoryId): Collection
    {
        return Product::where('category_id', $categoryId)->get();
    }

    /**
     * @param  string  $slug
     *
     * @return Product|null
     */
    public function getProductBySlug ( string $slug ): ?Product
    {
        return Product::where('slug', $slug)->first();
    }

    /**
     * @param  int  $productId
     * @param  array  $data
     *
     * @return Product
     */
    public function update ( int $productId, array $data ): Product
    {
       $product = $this->getProduct($productId);
       $product->update($data);

       return $product;
    }
}
