<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
Route::get('/', function () {
    return view('IELTS-paid');
})->name('welcome');

// Correct Route syntax
Route::post('/payment', [PaymentController::class, 'initialize']);

Route::post('/paymentIELTS', [PaymentController::class, 'initializeIELTS']);

Route::get('/payment-response', [PaymentController::class, 'paymentResponse'])->name('payment.response');


Route::post('/webhook/startbutton', [WebhookController::class, 'handleWebhook'])->name('WebhookHandler');

Route::get('/paid-code',  function () {
    return view(view: 'Paid-IELTS-code');
})->name('paidCode');
