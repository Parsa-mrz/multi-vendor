<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                @if($product->image)
                    <a href="{{ route('product.show', $product->slug) }}">
                        <img
                            src="{{ $product->image }}"
                            alt="{{ $product->name }}"
                            class="w-full h-48 object-cover"
                        >
                    </a>
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        No Image
                    </div>
                @endif

                <div class="p-4">
                    <a href="{{ route('product.show', $product->slug) }}">
                        <h3 class="text-lg font-semibold mb-2">{{ $product->name }}</h3>
                    </a>
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
                    </div>
                </div>
                    <livewire:cart.add-to-cart :productId="$product->id" :showQuantity="false" :key="'product_' . $product->id" />
            </div>
        @empty
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500">No products found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
