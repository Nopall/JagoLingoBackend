<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class iPaymuService
{
    protected $apiKey;
    protected $va;
    protected $url;

    public function __construct()
    {
        $this->apiKey = env('IPAYMU_API_KEY');
        $this->va = env('IPAYMU_VA');
        $this->url = 'https://my.ipaymu.com/api/v2/payment'; //gunakan untuk mode development
    }

    public function createPayment($amount, $transaction_id)
    {
        // Generate Signature
        $body = [
            'product' => ['Subscription JagoLingo'],
            'qty' => [1],
            'price' => [$amount],
            'returnUrl' => route('payment.success'),
            'cancelUrl' => route('payment.fail'),
            'notifyUrl' => route('ipaymu.callback'),
            'referenceId' => $transaction_id
        ];

        $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper('POST') . ':' . $this->va . ':' . $requestBody . ':' . $this->apiKey;
        $signature = hash_hmac('sha256', $stringToSign, $this->apiKey);
        $timestamp = now()->format('YmdHis');

        // Send Request
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'va' => $this->va,
            'signature' => $signature,
            'timestamp' => $timestamp
        ])->post($this->url, $body);

        return $response->json();
    }
}
