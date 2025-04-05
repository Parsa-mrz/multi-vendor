<?php

namespace App\Livewire\Products;

use App\Repositories\ProductRepository;
use Livewire\Attributes\Title;
use Livewire\Component;

class ProductShow extends Component
{
    public $slug;
    public $product;

    public function mount($slug, ProductRepository $productRepository)
    {
        $this->slug = $slug;
        $this->product = $productRepository->getProductBySlug($slug) ?? abort(404);
    }

    #[Title("Product-")]
    public function render()
    {
        return view('livewire.products.product-show');
    }
}
