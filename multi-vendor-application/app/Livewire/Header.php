<?php

namespace App\Livewire;

use App\Repositories\MessageRepository;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

use function count;

class Header extends Component
{
    public $cartCount = 0;
    public$unReadMessageCount = 0;

    public $user;

    public $menuItems = [
        'Home' => 'home',
        'Products' => 'shop',
    ];

    public function mount(CartService $cartService,MessageRepository $messageRepository)
    {
        $this->cartCount = count($cartService->getCartItems());
        $this->user = Auth::user();
        if($this->user){
            $this->unReadMessageCount = count($messageRepository->getAllUnreadMessages($this->user->id));
        }
    }

    #[On('cartUpdated')]
    public function updateCartCount(CartService $cartService)
    {
        $this->cartCount = count($cartService->getCartItems());
    }

    public function render()
    {
        return view('livewire.header');
    }
}
