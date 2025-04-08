<?php

namespace App\Helpers;

use Livewire\Component;

class SweetAlertHelper
{
    private $component;

    public function __construct(Component $component)
    {
        $this->component = $component;
    }
    public static function success(Component $component, string $title, string $text, string $redirect=null): void
    {
        $instance = new self($component);
        $instance->dispatchAlert('success', $title, $text, $redirect);
    }

    public static function error(Component $component, string $title, string $text, string $redirect=null): void
    {
        $instance = new self($component);
        $instance->dispatchAlert('error', $title, $text, $redirect);
    }

    public static function warning(Component $component, string $title, string $text, string $redirect=null): void
    {
        $instance = new self($component);
        $instance->dispatchAlert('warning', $title, $text, $redirect);

    }

    private function dispatchAlert(string $type, string $title, string $text, string $redirect = null): void
    {
        $this->component->dispatch('swal', [
            'type' => $type,
            'title' => $title,
            'text' => $text,
            'redirect' => $redirect,
        ]);
    }
}
