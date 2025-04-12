<?php

namespace App\Livewire\Products;

use App\Services\ProductService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $perPage = 8;

    #[Title('Shop')]
    public function render(ProductService $product_service)
    {
        $products = $product_service->getProducts($this->perPage);

        return view('livewire.products.product-list', [
            'products' => $products,
        ]);
    }
}
