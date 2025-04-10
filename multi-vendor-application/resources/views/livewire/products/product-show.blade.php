<div class="container mx-auto px-4 py-12 max-w-6xl">
    <div class="flex lg:flex-row gap-12">
        <!-- Product Image (Left Side) -->
        <div class="w-full lg:w-1/2">
            @if($product->image)
                <div class="rounded-2xl overflow-hidden shadow-lg bg-white p-4">
                    <img
                        src="{{ $product->image }}"
                        alt="{{ $product->name }}"
                        class="w-full h-auto max-h-[500px] object-contain"
                    >
                </div>
            @else
                <div class="w-full h-96 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center rounded-2xl shadow-lg">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-2 text-gray-500">No Image Available</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Product Details (Right Side) -->
        <div class="w-full lg:w-1/2">
            <div class="mb-6">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>

                <div class="flex items-center space-x-4 mb-4">
                    @if($product->sale_price)
                        <span class="text-gray-400 line-through text-xl">${{ number_format($product->price, 2) }}</span>
                        <span class="text-red-600 font-bold text-2xl">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="bg-red-100 text-red-800 text-sm font-semibold px-2.5 py-0.5 rounded-full">SAVE {{ number_format(100 - ($product->sale_price / $product->price * 100), 0) }}%</span>
                    @else
                        <span class="text-gray-800 font-bold text-2xl">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                @if($product->quantity > 0)
                    <div class="inline-flex items-center text-sm text-green-600 bg-green-50 px-3 py-1 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        In Stock ({{ $product->quantity }} available)
                    </div>
                @else
                    <div class="inline-flex items-center text-sm text-red-600 bg-red-50 px-3 py-1 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        Out of Stock
                    </div>
                @endif

                <div class="flex flex-col justify-between items-center items-center text-sm text-gray-600 bg-gray-50 px-4 py-4 rounded-full mb-4">
                    <span class="mb-2">
                        <span class="">
                             Vendor : {{ $product->vendor->store_name }}
                        </span>
                        <span class="block">
                            Description : {{$product->vendor->description}}
                        </span>
                    </span>
                    <span class="inline-flex items-center text-sm text-white bg-blue-500 px-3 py-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15H6a2.25 2.25 0 01-2.25-2.25V6.75A2.25 2.25 0 016 4.5h12a2.25 2.25 0 012.25 2.25v6A2.25 2.25 0 0118 15h-2.25M8.25 15v2.25a2.25 2.25 0 002.25 2.25h3.75a2.25 2.25 0 002.25-2.25V15M8.25 15h7.5" />
                        </svg>
                        @if(is_null ($user))
                            <a href="{{route ('login')}}">
                                Login To Chat With Vendor
                            </a>
                        @else
                            <a href="{{route ('chat.start',[$product->vendor->user_id])}}">
                                Chat With Vendor
                            </a>
                        @endif
                    </span>
                </div>
            </div>

            <div class="prose max-w-none mb-8 text-gray-700">
                {!! $product->description !!}
            </div>

            <div class="space-y-4">
                @if($product->quantity > 0)
                    <livewire:cart.add-to-cart :productId="$product->id" />
                @else
                    <div class="flex justify-center bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                        Out of Stock
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
