<?php

namespace App\Livewire\Cart;

use App\Helpers\SweetAlertHelper;
use App\Services\CartService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class AddToCart extends Component
{
    public $productId;
    public $quantity = 1;
    public $showQuantity = true;
    protected $cartService;

    protected $rules = [
        'quantity' => 'required|integer|min:1'
    ];

    public function boot(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function addToCart()
    {
        try {
            $this->validate();
            $this->cartService->addToCart($this->productId, $this->quantity);
            $this->quantity = 1;
            $this->dispatch('cartUpdated');

            SweetAlertHelper::success($this, 'Added to Cart', 'The item has been added successfully.');
        } catch (ValidationException $e) {
            $message = collect($e->validator->errors()->all())->first();
            SweetAlertHelper::warning($this, 'Validation Error', $message);
        } catch (\Exception $e) {
            SweetAlertHelper::error(
                $this,
                'Add to Cart Failed',
                $e->getMessage()
            );
        }
    }

    public function render()
    {
        return view('livewire.cart.add-to-cart');
    }
}
