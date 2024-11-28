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

    public function initializeTransaction($amount, $currency, $email)
    {
        $url = "{$this->baseUrl}/transaction/initialize";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->publicKey}",
            'Content-Type' => 'application/json',
        ])->post($url, [
            'amount' => $amount,
            'currency' => $currency,
            'email' => $email,
        ]);

        return $response->json();
    }
}
