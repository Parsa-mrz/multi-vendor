<?php

namespace App\Livewire\Checkout;

use App\Helpers\SweetAlertHelper;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

use function view;

class Checkout extends Component
{
    public $user;

    public $cartItems;

    public $subtotal;

    public function mount(CartService $cartService)
    {
        $this->user = Auth::user();
        $this->subtotal = $cartService->getTotal();
        $this->cartItems = $cartService->getCartItems();
        $this->user = $this->user->load('profile');
    }

    public function placeOrder(OrderService $orderService)
    {
        $data = [
            'order' => [
                'user_id' => $this->user->id,
                'total' => $this->subtotal,
                'subtotal' => $this->subtotal,
                'payment_method' => 'PayPal',
                'transaction_id' => 12312312,
            ],
            'items' => $this->cartItems,
        ];
        $orderService->createOrder($data);
        SweetAlertHelper::success(
            $this,
            'Order placed',
            'Your order has been placed'
        );
    }

    #[Title('Checkout')]
    public function render()
    {
        if (empty($this->cartItems)) {
            SweetAlertHelper::error(
                $this,
                'Cart is empty',
                '',
                route('cart'),
            );
        }

        return view('livewire.checkout.checkout');
    }
}
