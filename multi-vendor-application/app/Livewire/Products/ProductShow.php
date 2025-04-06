<?php

namespace App\Livewire\Products;

use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Livewire;

class ProductShow extends Component
{
    public $slug;
    public $product;

    public function mount($slug, ProductService $product_service)
    {
        $this->slug = $slug;
        $this->product = $product_service->getProductByAttribute ('slug', $slug);
    }

    public function render()
    {
        return view('livewire.products.product-show')
            ->title($this->product->name);
    }
}
