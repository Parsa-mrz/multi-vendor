<?php

namespace App\Livewire\Products;

use App\Repositories\ProductRepository;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $perPage = 12;
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }


    #[Title('Shop')]
    public function render(ProductRepository $productRepository)
    {
        $products = $productRepository->getProductsPaginated($this->perPage)
                                      ->when($this->search, function ($query) {
                                          return $query->where('name', 'like', '%' . $this->search . '%')
                                                       ->orWhere('description', 'like', '%' . $this->search . '%');
                                      });
        return view('livewire.products.product-list',[
            'products' => $products
        ]);
    }
}
