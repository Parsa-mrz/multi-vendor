<?php

use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);

    Route::get('/send-test-email', function () {
        try {
            Mail::raw('This is a test email to verify SMTP configuration.', function ($message) {
                $message->to('your_email@example.com')
                        ->subject('Test Email')
                        ->from('hello@example.com');
            });

            return 'Test email sent successfully!';
        } catch (\Exception $e) {
            return 'Failed to send email: ' . $e->getMessage();
        }
    });
});
