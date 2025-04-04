<?php

namespace App\Filament\Pages;

use App\Livewire\Dashboard\Main\CustomerStats;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return [
                CustomerStats::class,
            ];
        } elseif ($user->isCustomer()) {
            return [
                CustomerStats::class,
            ];
        } elseif ($user->isVendor()) {
            return [
                CustomerStats::class,
            ];
        }

        return [];
    }
}
