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

    public function initializeTransaction($amount, $currency, $email, $redirectUrl)
    {
        $redirectUrl = route('payment.response'); // Generate the callback URL dynamically
        $payload = [
            'amount' => $amount,
            'currency' => $currency,
            'email' => $email,
            'redirectUrl' => $redirectUrl, // Add redirect URL to payload
        ];

        $response = Http::withToken(env('STARTBUTTON_PUBLIC_KEY'))
                        ->post(config('services.startbutton.base_url') . '/transaction/initialize', $payload);

        return $response->json();
    }

}
