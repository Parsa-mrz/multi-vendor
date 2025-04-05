<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                @if($product->image)
                    <img
                        src="{{ $product->image }}"
                        alt="{{ $product->name }}"
                        class="w-full h-48 object-cover"
                    >
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        No Image
                    </div>
                @endif

                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $product->description }}</p>

                    <div class="flex items-center justify-between">
                        <div>
                            @if($product->sale_price)
                                <span class="text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                                <span class="text-red-600 font-bold">${{ number_format($product->sale_price, 2) }}</span>
                            @else
                                <span class="text-gray-800 font-bold">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        <a
                            href="{{ route('product.show', $product->slug) }}"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                        >
                            View
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500">No products found.</p>
            </div>
        @endforelse
    </div>

{{--    <div class="mt-8">--}}
{{--        {{ $products->links() }}--}}
{{--    </div>--}}
</div>
