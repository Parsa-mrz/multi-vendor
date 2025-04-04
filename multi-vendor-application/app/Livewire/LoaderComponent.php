<?php

namespace App\Livewire;

use Livewire\Component;

class LoaderComponent extends Component
{
    public $target = '';
    public $message = 'Processing your request...';

    public function mount($target = '', $message = null)
    {
        $this->target = $target;
        $this->message = $message ?? $this->message;
    }

    public function render()
    {
        return view('livewire.loader-component');
    }
}
