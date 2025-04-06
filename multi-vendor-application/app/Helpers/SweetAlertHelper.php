<?php

namespace App\Helpers;

use Livewire\Component;

class SweetAlertHelper
{
    public static function success(Component $component, string $title, string $text): void
    {
        $component->dispatch('swal', [
            'type' => 'success',
            'title' => $title,
            'text' => $text
        ]);
    }

    public static function error(Component $component, string $title, string $text): void
    {
        $component->dispatch('swal', [
            'type' => 'error',
            'title' => $title,
            'text' => $text
        ]);
    }

    public static function warning(Component $component, string $title, string $text): void
    {
        $component->dispatch('swal', [
            'type' => 'warning',
            'title' => $title,
            'text' => $text
        ]);
    }
}
