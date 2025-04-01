<?php

namespace App\Livewire;

use Livewire\Component;

abstract class BaseComponent extends Component
{
    protected $layout = 'components.layouts.app';

    abstract public function render();
}
