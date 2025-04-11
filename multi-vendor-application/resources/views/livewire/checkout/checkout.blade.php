<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Customer Information -->
        <div class="md:w-2/3">
            <div class="bg-white rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Customer Information</h2>

                <form wire:submit.prevent="placeOrder">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" id="firstName" wire:model="first_name" class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
                            @error('firstName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="lastName" wire:model="last_name" class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
                            @error('lastName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" disabled wire:model="email" class="mt-1 block w-full bg-gray-100 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" id="phone" wire:model="phone_number" class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" id="address" wire:model="address" class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
                        @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea type="text" id="notes" wire:model="notes" class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
                        </textarea>
                        @error('note') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="md:w-1/3">
            <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>

                <div class="space-y-4 mb-6">
                    @foreach($cartItems as $item)
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium">{{ $item['name'] }}</h3>
                                <p class="text-sm text-gray-500">Quantity: {{ $item['quantity'] }}</p>
                            </div>
                            <span>${{ number_format($item['sale_price'] ? $item['sale_price']* $item['quantity']: $item['price'] * $item['quantity'], 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-200 pt-4 space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tax</span>
                        <span>$0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span>$0</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg pt-2">
                        <span>Total</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                </div>

                <button wire:click="placeOrder" class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Place Order
                </button>
            </div>
        </div>
    </div>
</div>
