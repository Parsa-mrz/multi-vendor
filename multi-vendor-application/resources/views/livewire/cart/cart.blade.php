<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <!-- Heading -->
    <h2 class="text-2xl font-bold text-center mb-6">Shopping Cart</h2>

    @if(count($cartItems) > 0)
        <div class="space-y-4">
            @foreach($cartItems as $item)
                <div class="p-4 border border-gray-200 rounded-md">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $item['name'] }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                @if( ($item['discount']) && ($item['sale_price']))
                                    <span class="line-through text-gray-400">${{ number_format($item['price'], 2) }}</span>
                                    <span class="text-red-600 font-semibold ml-2">${{ number_format($item['sale_price'], 2) }}</span>
                                @else
                                    <span>${{ number_format($item['price'], 2) }}</span>
                                @endif
                            </p>
                        </div>

                        <button
                            wire:click="removeItem({{ $item['id'] }})"
                            class="text-gray-400 hover:text-red-600"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="mt-3 flex items-center justify-between">
                        <input
                            type="number"
                            wire:model.debounce.500ms="cartItems.{{ $item['id'] }}.quantity"
                            wire:change="updateQuantity({{ $item['id'] }}, $event.target.value)"
                            min="1"
                            class="w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <p class="text-gray-700 font-medium">
                            ${{ number_format(($item['discount'] ? $item['sale_price'] : $item['price']) * $item['quantity'], 2) }}
                        </p>
                    </div>
                </div>
            @endforeach

            <!-- Total -->
            <div class="p-4 bg-gray-50 rounded-md">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-900">Total:</span>
                    <span class="text-xl font-bold text-gray-900">${{ number_format($total, 2) }}</span>
                </div>
            </div>

            <!-- Checkout Button (you can add this if needed) -->
            <a
                href="{{route ('checkout')}}"
                class="w-full block text-center py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Proceed to Checkout
            </a>
        </div>
    @else
        <div class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="mt-4 text-gray-600">Your cart is empty</p>
        </div>
    @endif
</div>
