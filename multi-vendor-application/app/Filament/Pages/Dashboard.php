<?php

namespace App\Filament\Pages;

use App\Livewire\Dashboard\Main\CustomerStates;
use App\Livewire\Dashboard\Main\TopProducts;
use App\Livewire\Dashboard\Main\VendorOrderChart;
use Dotswan\FilamentLaravelPulse\Widgets\PulseCache;
use Dotswan\FilamentLaravelPulse\Widgets\PulseExceptions;
use Dotswan\FilamentLaravelPulse\Widgets\PulseQueues;
use Dotswan\FilamentLaravelPulse\Widgets\PulseServers;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowOutGoingRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowQueries;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseUsage;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;


class Dashboard extends BaseDashboard
{

    public function getColumns(): int|string|array
    {
        $user = Auth::user();
        if($user->isAdmin ()){
            return 12;
        }

        return 2;

    }
    public function getWidgets(): array
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return [
                PulseServers::class,
                PulseCache::class,
                PulseExceptions::class,
                PulseUsage::class,
                PulseQueues::class,
                PulseSlowQueries::class,
                PulseSlowRequests::class,
                PulseSlowOutGoingRequests::class
            ];
        } elseif ($user->isCustomer()) {
            return [
                CustomerStates::class,
            ];
        } elseif ($user->isVendor()) {
            return [
                TopProducts::class,
                VendorOrderChart::class,
            ];
        }

        return [];
    }
}
