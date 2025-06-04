<?php

namespace App\Services;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PesapalService
{
    protected $baseUrl;
    protected $key;
    protected $secret;

    public function __construct()
    {
        $this->baseUrl = config('pesapal.base_url');
        $this->key = config('pesapal.consumer_key');
        $this->secret = config('pesapal.consumer_secret');
    }

     public function getAccessToken(): string
    {
        $baseUrl = config('pesapal.base_url');
        $key = config('pesapal.consumer_key');
        $secret = config('pesapal.consumer_secret');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post("{$baseUrl}/api/Auth/RequestToken", [
            'consumer_key' => $key,
            'consumer_secret' => $secret,
        ]);

        if ($response->successful() && isset($response['token'])) {
            return $response['token'];

                // ✅ Log the token for debugging
            Log::info('Pesapal Access Token Retrieved', [
                'token' => $token
            ]);
        }

       

        Log::error('Pesapal Auth Error', [
        'status' => $response->status(),
        'body' => $response->body(),
    ]);
        

        throw new \Exception('Failed to retrieve access token from Pesapal.');
    }

    public function initiatePayment(array $data)
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/api/Transactions/SubmitOrderRequest", $data);

        return $response->json();
    }
}
