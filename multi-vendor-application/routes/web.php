<?php

use App\Http\Middleware\RedirectIfAuthenticated;
use App\Livewire\Auth\AuthComponent;
use App\Livewire\Cart\Cart;
use App\Livewire\HomePage;
use App\Livewire\Products\ProductList;
use App\Livewire\Products\ProductShow;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');
Route::get('login', AuthComponent::class)->name('login')->middleware([RedirectIfAuthenticated::class]);
Route::get ('products',ProductList::class)->name('shop');
Route::get ('products/{slug}',ProductShow::class)->name ('product.show');
Route::get ('cart', Cart::class)->name ('cart');
