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

    // Initialize transaction
    public function initializeTransaction($amount, $currency, $email, $redirectUrl)
    {
        $payload = [
            'amount' => $amount,
            'currency' => $currency,
            'email' => $email,
            'redirectUrl' => $redirectUrl, // Add redirect URL to payload
        ];

        $response = Http::withToken(env('STARTBUTTON_PUBLIC_KEY'))
                        ->post($this->baseUrl . '/transaction/initialize', $payload);

        return $response->json();
    }

    // Retrieve transaction status by ID
    public function getTransactionStatus($transactionId)
    {
        $response = Http::withToken(env('STARTBUTTON_PUBLIC_KEY'))
                        ->get($this->baseUrl . "/transaction/{$transactionId}/status");

        return $response->json();
    }
}
