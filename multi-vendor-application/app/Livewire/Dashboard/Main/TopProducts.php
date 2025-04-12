<?php

namespace App\Livewire\Dashboard\Main;

use App\Models\Order;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TopProducts extends Widget
{
    protected static string $view = 'livewire.top-products';
    protected int | string | array $columnSpan = 'full';

    public function getData(): array
    {
        $user = Auth::user();

        $topProducts = Order::query()
                            ->whereHas('items.product', function ($q) use ($user) {
                                $q->where('vendor_id', $user->vendor->id);
                            })
                            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                            ->join('products', 'order_items.product_id', '=', 'products.id')
                            ->select(
                                'products.id',
                                'products.name',
                                DB::raw('SUM(order_items.quantity) as total_quantity'),
                                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
                            )
                            ->groupBy('products.id', 'products.name')
                            ->orderByDesc('total_quantity')
                            ->take(3)
                            ->get()
                            ->toArray();

        return $topProducts;
    }
}
