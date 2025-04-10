<?php

namespace App\Livewire\Dashboard\Main;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class CustomerStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        $totalOrders =  $user->orders()->count();
        $pendingOrders =  $user->orders()->where('status', 'pending')->count();

        return [
            Stat::make('Register Duration', $user->getRegisterDurationInDays())
                ->description('Since: '.$user->getFormattedRegistrationDate())
                ->color('warning'),
            Stat::make('Your Orders', $totalOrders)
                ->description('Total orders placed')
                ->color('primary'),
            Stat::make('Pending Orders', $pendingOrders)
                ->description('Orders awaiting action')
                ->color('warning'),
        ];
    }
}
