<?php

namespace App\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getProducts(): ?Collection;
    public function getProductsPaginated(int $perPage=15): LengthAwarePaginator;
    public function getProduct(int $id): ?Product;
    public function findByVendorId(int $vendorId): ?Collection;
    public function findByCategoryId(int $categoryId): ?Collection;

    public function getProductBySlug(string $slug): ?Product;
}
