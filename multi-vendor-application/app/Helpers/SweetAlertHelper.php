<?php

namespace App\Helpers;

use Livewire\Component;

/**
 * Helper class to dispatch SweetAlert notifications from Livewire components.
 *
 * Provides methods for sending success, error, and warning alerts.
 */
class SweetAlertHelper
{
    /**
     * The Livewire component instance.
     *
     * @var \Livewire\Component
     */
    private $component;

    /**
     * Create a new SweetAlertHelper instance.
     *
     * @param  \Livewire\Component  $component  The Livewire component instance.
     */
    public function __construct(Component $component)
    {
        $this->component = $component;
    }

    /**
     * Dispatch a success alert to the Livewire component.
     *
     * @param  \Livewire\Component  $component  The Livewire component instance.
     * @param  string  $title  The title of the alert.
     * @param  string  $text  The text message of the alert.
     * @param  string|null  $redirect  Optional URL to redirect to after the alert.
     */
    public static function success(Component $component, string $title, string $text, ?string $redirect = null): void
    {
        $instance = new self($component);
        $instance->dispatchAlert('success', $title, $text, $redirect);
    }

    /**
     * Dispatch an error alert to the Livewire component.
     *
     * @param  \Livewire\Component  $component  The Livewire component instance.
     * @param  string  $title  The title of the alert.
     * @param  string  $text  The text message of the alert.
     * @param  string|null  $redirect  Optional URL to redirect to after the alert.
     */
    public static function error(Component $component, string $title, string $text, ?string $redirect = null): void
    {
        $instance = new self($component);
        $instance->dispatchAlert('error', $title, $text, $redirect);
    }

    /**
     * Dispatch a warning alert to the Livewire component.
     *
     * @param  \Livewire\Component  $component  The Livewire component instance.
     * @param  string  $title  The title of the alert.
     * @param  string  $text  The text message of the alert.
     * @param  string|null  $redirect  Optional URL to redirect to after the alert.
     */
    public static function warning(Component $component, string $title, string $text, ?string $redirect = null): void
    {
        $instance = new self($component);
        $instance->dispatchAlert('warning', $title, $text, $redirect);

    }

    /**
     * Dispatch the SweetAlert event with the specified type, title, and text.
     *
     * @param  string  $type  The type of the alert (success, error, warning).
     * @param  string  $title  The title of the alert.
     * @param  string  $text  The text message of the alert.
     * @param  string|null  $redirect  Optional URL to redirect to after the alert.
     */
    private function dispatchAlert(string $type, string $title, string $text, ?string $redirect = null): void
    {
        $this->component->dispatch('swal', [
            'type' => $type,
            'title' => $title,
            'text' => $text,
            'redirect' => $redirect,
        ]);
    }
}
