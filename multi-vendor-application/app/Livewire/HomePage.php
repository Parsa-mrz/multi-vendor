<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class HomePage extends Component
{
    protected $layout = 'components.layouts.app';

    #[Title('Home')]
    public function render()
    {
        return view('livewire.home-page');
    }
}
