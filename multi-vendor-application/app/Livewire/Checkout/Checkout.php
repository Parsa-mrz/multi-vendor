<?php

namespace App\Livewire\Checkout;

use App\Repositories\UserRepository;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

class Checkout extends Component
{
    public $user;
    public $cartItems;
    public $subtotal;

    public function mount (CartService $cartService)
    {
        $user= Auth::user();
        $this->subtotal = $cartService->getTotal ();
        $this->cartItems = $cartService->getCartItems ();
        $this->user = $user->load ('profile');
    }

    #[Title("Checkout")]
    public function render()
    {
        return view('livewire.checkout.checkout');
    }
}
