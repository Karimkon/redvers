<?php

namespace App\Services;

class MtnMomoService
{
    protected $baseUrl;
    protected $subscriptionKey;
    protected $apiUser;
    protected $apiKey;
    protected $targetEnvironment;

    public function __construct()
    {
        $this->baseUrl = config('services.mtn.base_url');
        $this->subscriptionKey = config('services.mtn.subscription_key');
        $this->apiUser = config('services.mtn.api_user');
        $this->apiKey = config('services.mtn.api_key');
        $this->targetEnvironment = config('services.mtn.target_env', 'sandbox');
    }

    public function getAccessToken()
    {
        $url = $this->baseUrl . "/collection/token/";
        $encodedToken = base64_encode("{$this->apiUser}:{$this->apiKey}");

        $headers = [
            "Ocp-Apim-Subscription-Key: {$this->subscriptionKey}",
            "Authorization: Basic {$encodedToken}",
            "X-Target-Environment: {$this->targetEnvironment}"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ""); // empty body
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['access_token'])) {
            return $result['access_token'];
        }

        throw new \Exception("Access token failed with status {$http_code}: " . json_encode($result));
    }

    public function requestToPay($phoneNumber, $amount, $currency = 'UGX', $externalId = null, $payerMessage = 'Battery Swap Payment', $payeeNote = 'Battery Swap')
    {
        $uuid = \Illuminate\Support\Str::uuid()->toString();
        $accessToken = $this->getAccessToken();
        $url = $this->baseUrl . "/collection/v1_0/requesttopay";

        $payload = [
            "amount" => (string) $amount,
            "currency" => 'EUR',
            "externalId" => $externalId ?? uniqid(),
            "payer" => [
                "partyIdType" => "MSISDN",
                "partyId" => $phoneNumber
            ],
            "payerMessage" => $payerMessage,
            "payeeNote" => $payeeNote
        ];

        $headers = [
            "Authorization: Bearer {$accessToken}",
            "X-Reference-Id: {$uuid}",
            "X-Target-Environment: {$this->targetEnvironment}",
            "Ocp-Apim-Subscription-Key: {$this->subscriptionKey}",
            "Content-Type: application/json"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 202) {
            return $uuid;
        }

        throw new \Exception("Request to pay failed with status {$http_code}: " . $response);
    }
}
