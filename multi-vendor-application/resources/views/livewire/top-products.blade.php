<x-filament-widgets::widget>
    <x-filament::section>
            <h2 class="text-lg font-semibold">Top 3 Best-Selling Products</h2>
            @if (empty($this->getData()))
                <p class="text-gray-500 mt-4">No products found.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    @foreach ($this->getData() as $product)
                        <x-filament::card>
                            <div class=" p-4 rounded-lg shadow-sm">
                                <h3 class="text-md font-medium truncate">{{ $product['name'] }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Sold: <span class="font-semibold">{{ $product['total_quantity'] }}</span> units
                                </p>
                                <p class="text-sm text-gray-600">
                                    Revenue: <span class="font-semibold">${{ number_format($product['total_revenue'], 2) }}</span>
                                </p>
                            </div>
                        </x-filament::card>
                    @endforeach
                </div>
            @endif
    </x-filament::section>
</x-filament-widgets::widget>
