<?php

namespace App\Livewire\Cart;

use App\Helpers\SweetAlertHelper;
use App\Services\CartService;
use Livewire\Attributes\Title;
use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];
    public $total = 0;
    protected $cartService;

    protected $listeners = [
        'cartUpdated' => 'refreshCart'
    ];

    public function boot(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {
        try {
            $this->cartItems = $this->cartService->getCartItems();
            $this->total = $this->cartService->getTotal();
        } catch (\Exception $e) {
            SweetAlertHelper::error($this, 'Operation Failed', $e->getMessage());
        }
    }

    public function removeItem($productId)
    {
        try {
            $this->cartService->removeFromCart($productId);
            $this->refreshCart();
            $this->dispatch('cartUpdated');
            SweetAlertHelper::success($this,'Cart Updated','Item removed from cart!');
        } catch (\Exception $e) {
            SweetAlertHelper::error($this, 'Operation Failed', $e->getMessage());
        }
    }

    public function updateQuantity($productId, $quantity)
    {
        try {
            $this->cartService->updateCartQuantity($productId, $quantity);
            $this->refreshCart();
            $this->dispatch('cartUpdated');
            SweetAlertHelper::success($this,'Cart Updated','Quantity updated successfully!');
        } catch (\Exception $e) {
            SweetAlertHelper::error($this, 'Operation Failed', $e->getMessage());
        }
    }

    #[Title('Cart')]
    public function render()
    {
        return view('livewire.cart.cart');
    }
}
