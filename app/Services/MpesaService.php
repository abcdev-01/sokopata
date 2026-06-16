<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    /**
     * @var string|null
     */
    protected $consumerKey;
    
    /**
     * @var string|null
     */
    protected $consumerSecret;
    
    /**
     * @var string|null
     */
    protected $shortcode;
    
    /**
     * @var string|null
     */
    protected $passkey;
    
    /**
     * @var string
     */
    protected $environment;
    
    /**
     * @var string
     */
    protected $baseUrl;

    public function __construct()
    {
        $this->consumerKey = env('MPESA_CONSUMER_KEY');
        $this->consumerSecret = env('MPESA_CONSUMER_SECRET');
        $this->shortcode = env('MPESA_SHORTCODE');
        $this->passkey = env('MPESA_PASSKEY');
        $this->environment = env('MPESA_ENVIRONMENT', 'sandbox');
        
        $this->baseUrl = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    /**
     * Get OAuth Access Token
     */
    public function getAccessToken(): ?string
    {
        if (!$this->consumerKey || !$this->consumerSecret) {
            Log::error('M-Pesa credentials not configured');
            return null;
        }

        $url = $this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials';
        
        try {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->timeout(30)
                ->get($url);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            Log::error('M-Pesa Token Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('M-Pesa Token Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * STK Push (Lipa Na M-Pesa Online)
     */
    public function stkPush(string $phoneNumber, float $amount, string $accountReference, string $transactionDesc): array
    {
        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return ['error' => 'Failed to get access token'];
        }

        // Format phone number (remove leading 0 or +254)
        $phoneNumber = preg_replace('/^0/', '254', $phoneNumber);
        $phoneNumber = preg_replace('/^\+/', '', $phoneNumber);
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        $timestamp = now()->format('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $url = $this->baseUrl . '/mpesa/stkpush/v1/processrequest';

        $data = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => (int) $amount,
            'PartyA' => $phoneNumber,
            'PartyB' => $this->shortcode,
            'PhoneNumber' => $phoneNumber,
            'CallBackURL' => route('mpesa.callback'),
            'AccountReference' => $accountReference,
            'TransactionDesc' => $transactionDesc
        ];

        try {
            $response = Http::withToken($accessToken)
                ->timeout(30)
                ->post($url, $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('M-Pesa STK Push Error: ' . $response->body());
            return ['error' => 'STK Push failed: ' . $response->body()];
        } catch (\Exception $e) {
            Log::error('M-Pesa STK Push Exception: ' . $e->getMessage());
            return ['error' => 'STK Push exception: ' . $e->getMessage()];
        }
    }

    /**
     * Query Transaction Status
     */
    public function queryStatus(string $checkoutRequestId): ?array
    {
        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return ['error' => 'Failed to get access token'];
        }

        $timestamp = now()->format('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $url = $this->baseUrl . '/mpesa/stkpushquery/v1/query';

        $data = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestId
        ];

        try {
            $response = Http::withToken($accessToken)
                ->timeout(30)
                ->post($url, $data);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('M-Pesa Query Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if M-Pesa is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->consumerKey) && 
               !empty($this->consumerSecret) && 
               !empty($this->shortcode) && 
               !empty($this->passkey);
    }
}