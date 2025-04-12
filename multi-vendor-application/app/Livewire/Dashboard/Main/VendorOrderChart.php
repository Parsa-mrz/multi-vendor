<?php

namespace App\Livewire\Dashboard\Main;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class VendorOrderChart extends ChartWidget
{
    protected static ?string $heading = 'Orders';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $user = Auth::user();

        $orders = Order::whereHas('items.product', function ($q) use ($user) {
            $q->where('vendor_id', $user->vendor->id);
        })
                       ->whereYear('created_at', now()->year)
                       ->selectRaw("EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count")
                       ->groupBy('month')
                       ->pluck('count', 'month')
                       ->toArray();

        $orderCounts = array_fill(1, 12, 0);
        foreach ($orders as $month => $count) {
            $orderCounts[$month] = $count;
        }
        $orderCounts = array_values($orderCounts);

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $orderCounts,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
