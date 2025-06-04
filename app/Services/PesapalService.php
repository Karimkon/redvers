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

     public function requestAccessToken()
    {
        $url = $this->baseUrl . '/api/Auth/RequestToken';

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $body = [
            'consumer_key' => $this->key,
            'consumer_secret' => $this->secret,
        ];

        $response = Http::withHeaders($headers)->post($url, $body);

        return $response->json(); // Will contain token or error
    }

    public function initiatePayment(array $payload)
    {
        $token = $this->getAccessToken(); // Reuse token if still valid
        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/api/Transactions/SubmitOrderRequest", $payload);

        if ($response->successful()) {
            return $response->json();
        }

        \Log::error('Pesapal SubmitOrderRequest Error', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        throw new \Exception('Failed to submit order to Pesapal.');
    }

  public function getAccessToken(): string
    {
        $url = $this->baseUrl . '/api/Auth/RequestToken';

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($url, [
            'consumer_key' => $this->key,
            'consumer_secret' => $this->secret,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if (!isset($data['token'])) {
                Log::error('Pesapal token missing from response', ['body' => $data]);
                throw new \Exception('Access token missing from Pesapal response.');
            }

            return $data['token'];
        }

        Log::error('Pesapal Token Request Failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        throw new \Exception('Failed to retrieve access token from Pesapal.');
    }



}
