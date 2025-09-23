<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ClickPesaService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->baseUrl = config('clickpesa.base_url', env('CLICKPESA_BASE_URL'));
        $this->clientId = env('CLICKPESA_CLIENT_ID');
        $this->clientSecret = env('CLICKPESA_CLIENT_SECRET');
    }

    // Get Access Token
    public function getAccessToken()
    {
        $response = Http::post($this->baseUrl . '/auth/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
        ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception('Could not get ClickPesa access token: ' . $response->body());
    }

    // Initiate USSD payment
    public function initiateUSSD($phone, $amount, $currency = 'TZS', $reference)
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)->post($this->baseUrl . '/payments/ussd', [
            'phone' => $phone,
            'amount' => $amount,
            'currency' => $currency,
            'reference' => $reference,
            'callback_url' => env('CLICKPESA_CALLBACK_URL'),
        ]);

        return $response->json();
    }

    // Check payment status
    public function checkStatus($transactionId)
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)->get($this->baseUrl . "/payments/{$transactionId}/status");

        return $response->json();
    }
}
