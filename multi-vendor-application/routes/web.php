<?php

use App\Http\Middleware\RedirectIfAuthenticated;
use App\Livewire\Auth\AuthComponent;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;


Route::get('/', HomePage::class)->name('home');
Route::get ('login',AuthComponent::class)->name ('login')->middleware ([RedirectIfAuthenticated::class]);
