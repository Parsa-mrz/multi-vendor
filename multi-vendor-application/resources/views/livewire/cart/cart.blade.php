<div class="container mx-auto px-4 py-8 max-w-4xl">
    @if ($errors->has('cart'))
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
            {{ $errors->first('cart') }}
        </div>
    @endif

    <h2 class="text-2xl font-bold text-gray-900 mb-6">Shopping Cart</h2>

    @if(count($cartItems) > 0)
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="divide-y divide-gray-200">
                @foreach($cartItems as $item)
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $item['name'] }}</h3>
                                <p class="text-sm text-gray-600">
                                    @if($item['discount'] && $item['sale_price'])
                                        <span class="line-through text-gray-400">${{ number_format($item['price'], 2) }}</span>
                                        <span class="text-red-600 font-semibold">${{ number_format($item['sale_price'], 2) }}</span>
                                    @else
                                        <span>${{ number_format($item['price'], 2) }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <input
                                type="number"
                                wire:model.debounce.500ms="cartItems.{{ $item['id'] }}.quantity"
                                wire:change="updateQuantity({{ $item['id'] }}, $event.target.value)"
                                min="1"
                                class="w-20 px-2 py-1 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                            <p class="text-gray-700 font-medium">
                                ${{ number_format(($item['discount'] ? $item['sale_price'] : $item['price']) * $item['quantity'], 2) }}
                            </p>
                            <button
                                wire:click="removeItem({{ $item['id'] }})"
                                class="text-red-600 hover:text-red-800"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-4 bg-gray-50">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-900">Total:</span>
                    <span class="text-xl font-bold text-gray-900">${{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="mt-2 text-gray-600">Your cart is empty</p>
        </div>
    @endif
</div>
