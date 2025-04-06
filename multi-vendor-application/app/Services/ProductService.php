<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use function is_null;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public  function getProducts ($perPage = null): Collection|LengthAwarePaginator
    {
        if(!is_null ($perPage)) {
            return $this->productRepository->getProductsPaginated ($perPage);
        }
        return $this->productRepository->getProducts ();
    }

    public function getProductByAttribute (string $attribute, string|int $value): Product
    {
        return match ($attribute) {
            'id' => $this->productRepository->getProduct ($value),
            'vendor_id' => $this->productRepository->findByVendorId ($value),
            'slug' => $this->productRepository->getProductBySlug ($value),
            'category_id' => $this->productRepository->findByCategoryId ($value),
            default => $this->productRepository->getProduct ($value),
        };
    }

}
