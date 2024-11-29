<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Correct Route syntax
Route::post('/payment', [PaymentController::class, 'initialize']);

Route::get('/payment-response', [PaymentController::class, 'paymentResponse'])->name('payment.response');
