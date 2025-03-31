<?php

use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');
Route::get('test-mail', function () {
    $content = '<h1>Hello!</h1><p>This is a test email from Laravel.</p><p>✅ SMTP is working!</p>';

    Mail::send([], [], function ($message) use ($content) {
        $message->to('recipient@example.com')
              ->subject('Test Email')
              ->setBody($content, 'text/html');
    });

    return 'Email Sent!';
});
