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

        return [
            Stat::make('Register Duration', $user->getRegisterDurationInDays () . ' days')
                ->description('Since: ' . $user->getFormattedRegistrationDate())
                ->color('warning'),
            Stat::make('Your Orders', 10)
                ->description('Total orders placed')
                ->color('primary'),
            Stat::make('Pending Orders', 20)
                ->description('Orders awaiting action')
                ->color('warning'),
        ];
    }
}
