<?php

namespace App\Livewire\Checkout;

use App\Enums\PaymentStatus;
use App\Helpers\SweetAlertHelper;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\Payment\PaymentGatewayFactory;
use App\Services\Payment\PaymentService;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Title;
use Livewire\Component;

use function dd;
use function route;
use function view;

class Checkout extends Component
{
    public $user;
    public $first_name;
    public $last_name;
    public $phone_number;
    public $address;
    public $email;
    public $notes;
    public $cartItems;
    public $subtotal;
    public $isThankYou = false;
    public $order;
    public $payment_method = 'paypal';

    public function mount(CartService $cartService)
    {
        if(!Auth::check()){
            Session::put ('login',[
                'message' => 'You must be logged in to checkout',
                'route' => route('checkout')
            ]);
            $this->redirect (route ('login'));
            return;
        }
        $this->user = Auth::user();
        $this->subtotal = $cartService->getTotal();
        $this->cartItems = $cartService->getCartItems();
        $this->user = $this->user->load('profile');
        $this->setProfileData ();
    }

    public function placeOrder(OrderService $orderService,ProfileService $profileService,PaymentService $paymentService)
    {
        $paymentResult = $paymentService->charge ($this->payment_method, $this->subtotal);

        if (!$paymentService->isPaymentSuccessful($paymentResult)) {
            SweetAlertHelper::error(
                $this,
                'Payment Failed',
                'There was an issue processing your payment. Please try again.',
            );
            return;
        }

        $data = [
            'order' => [
                'user_id' => $this->user->id,
                'total' => $this->subtotal,
                'subtotal' => $this->subtotal,
                'payment_method' => $this->payment_method,
                'transaction_id' => $paymentResult->getTransactionReference(),
                'address' => $this->address,
                'notes' => $this->notes,
                'payment_status' =>PaymentStatus::PAID->value,
            ],
            'items' => $this->cartItems,
        ];
        $this->order = $orderService->createOrder($data);

        $profileData = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
        ];
        $profileService->updateProfile($this->user->id,$profileData);

        SweetAlertHelper::success(
            $this,
            'Order placed',
            'Your order has been placed',
        );
        $this->isThankYou = true;
    }

    private function setProfileData ()
    {
        $this->first_name = $this->user->profile->first_name ?? '';
        $this->last_name = $this->user->profile->last_name ?? '';
        $this->phone_number = $this->user->profile->phone_number ?? '';
        $this->address = $this->user->profile->address ?? '';
        $this->email = $this->user->email ?? '';
    }

    #[Title('Checkout')]
    public function render()
    {
        if (empty($this->cartItems)) {
            SweetAlertHelper::error(
                $this,
                'Cart is empty',
                '',
                route('shop'),
            );
        }

        return view('livewire.checkout.checkout');
    }
}
