<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class StartButtonService
{
    protected $baseUrl;
    protected $publicKey;

    public function __construct()
    {
        $this->baseUrl = config('services.startbutton.base_url');
        $this->publicKey = config('services.startbutton.public_key');
    }

    // Initialize a payment transaction
    public function initializeTransaction($amount, $currency, $email, $redirectUrl)
    {
        $payload = [
            'amount' => $amount,
            'currency' => $currency,
            'email' => $email,
            'redirectUrl' => $redirectUrl, // The callback URL after the payment
        ];

        // Send a POST request to the payment provider
        $response = Http::withToken(env('STARTBUTTON_PUBLIC_KEY'))
                        ->post($this->baseUrl . '/transaction/initialize', $payload);

        return $response->json(); // Return the response as an array
    }
}
