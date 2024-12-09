<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Correct Route syntax
Route::post('/payment', [PaymentController::class, 'initialize']);

Route::get('/payment-response', [PaymentController::class, 'paymentResponse']);


Route::post('/webhook/startbutton', [WebhookController::class, 'handleWebhook'])->name('WebhookHandler');
