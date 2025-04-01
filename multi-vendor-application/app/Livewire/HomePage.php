<?php

namespace App\Livewire;

use Livewire\Attributes\Title;

class HomePage extends BaseComponent
{
    #[Title('Home')]
    public function render()
    {
        return view('livewire.home-page');
    }
}
