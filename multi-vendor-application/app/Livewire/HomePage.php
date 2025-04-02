<?php

namespace App\Livewire;

use Livewire\Attributes\Title;

/**
 * Class HomePage
 *
 * This Livewire component represents the homepage of the application.
 *
 * @package App\Livewire
 */
class HomePage extends BaseComponent
{

    /**
     * Render the Livewire component view.
     *
     * @return \Illuminate\View\View
     */
    #[Title('Home')]
    public function render()
    {
        return view('livewire.home-page');
    }
}
