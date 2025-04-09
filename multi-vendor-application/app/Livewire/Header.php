<?php

namespace App\Livewire;

use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Header extends Component
{
    public $cartCount = 0;
    public $user;
    public $menuItems = [
        'Home' => '',
        'Products' => '',
        'About Us' => '',
    ];

    public function mount (CartService $cartService)
    {
        $this->cartCount = count($cartService->getCartItems());
        $this->user= Auth::user();
    }
    public function render()
    {
        return view('livewire.header');
    }
}
