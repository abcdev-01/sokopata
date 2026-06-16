<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PesapalService
{
    protected string $consumerKey;
    protected string $consumerSecret;
    protected string $environment;
    protected string $baseUrl;
    protected string $queryUrl;
    protected string $callbackUrl;
    protected string $ipnUrl;
    protected bool $simulationMode;

    public function __construct()
    {
        $this->consumerKey = env('PESAPAL_CONSUMER_KEY', '');
        $this->consumerSecret = env('PESAPAL_CONSUMER_SECRET', '');
        $this->environment = env('PESAPAL_ENVIRONMENT', 'sandbox');
        $this->simulationMode = env('PESAPAL_SIMULATION_MODE', true);
        
        if ($this->environment === 'production') {
            $this->baseUrl = 'https://www.pesapal.com/API/PostPesapalDirectOrderV4';
            $this->queryUrl = 'https://www.pesapal.com/API/QueryPaymentDetails';
        } else {
            $this->baseUrl = 'https://demo.pesapal.com/API/PostPesapalDirectOrderV4';
            $this->queryUrl = 'https://demo.pesapal.com/API/QueryPaymentDetails';
        }
            
        $this->callbackUrl = env('PESAPAL_CALLBACK_URL', route('pesapal.callback'));
        $this->ipnUrl = env('PESAPAL_IPN_URL', route('pesapal.ipn'));
    }

    public function submitOrder(Order $order, float $amount, string $phoneNumber, string $email): array
    {
        if ($this->simulationMode || !$this->isConfigured()) {
            return $this->simulatePayment($order, $amount, $phoneNumber, $email);
        }

        $orderId = $order->order_number;
        
        $requestData = [
            'merchant_reference' => $orderId,
            'currency' => 'TZS',
            'amount' => number_format($amount, 2, '.', ''),
            'description' => 'SokoPata Payment for Order #' . $orderId,
            'type' => 'MERCHANT',
            'first_name' => $order->buyer->name ?? 'Customer',
            'last_name' => '',
            'email' => $email,
            'phone_number' => $phoneNumber,
            'redirect_mode' => '',
            'callback_url' => $this->callbackUrl,
            'notification_id' => $this->ipnUrl
        ];

        $oauthParams = [
            'oauth_consumer_key' => $this->consumerKey,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_nonce' => $this->generateNonce(),
            'oauth_version' => '1.0',
        ];

        $oauthParams['pesapal_request_data'] = json_encode($requestData);
        $signature = $this->generateSignature($oauthParams);
        $oauthParams['oauth_signature'] = $signature;

        try {
            $response = Http::asForm()
                ->timeout(60)
                ->post($this->baseUrl, $oauthParams);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('Pesapal Response:', $result);
                
                if (isset($result['pesapal_redirect_url'])) {
                    return [
                        'success' => true,
                        'redirect_url' => $result['pesapal_redirect_url'],
                        'transaction_tracking_id' => $result['pesapal_transaction_tracking_id'] ?? null
                    ];
                }
                
                return [
                    'success' => false,
                    'error' => 'No redirect URL received',
                    'response' => $result
                ];
            }

            Log::error('Pesapal Error:', ['response' => $response->body()]);
            return [
                'success' => false,
                'error' => 'Payment submission failed',
                'details' => $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('Pesapal Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Payment submission failed',
                'details' => $e->getMessage()
            ];
        }
    }

    protected function simulatePayment(Order $order, float $amount, string $phoneNumber, string $email): array
    {
        Log::info('Pesapal Simulation Mode: Processing payment for order ' . $order->order_number);
        
        $transactionId = 'SIM_' . strtoupper(Str::random(10));
        
        return [
            'success' => true,
            'redirect_url' => route('pesapal.simulate.success', $order),
            'transaction_tracking_id' => $transactionId,
            'simulation' => true
        ];
    }

    protected function generateSignature(array $params): string
    {
        $method = 'POST';
        $baseString = $method . '&' . rawurlencode($this->baseUrl) . '&';
        
        ksort($params);
        
        $paramString = '';
        foreach ($params as $key => $value) {
            if ($paramString !== '') {
                $paramString .= '&';
            }
            $paramString .= rawurlencode($key) . '=' . rawurlencode($value);
        }
        
        $baseString .= rawurlencode($paramString);
        $signingKey = rawurlencode($this->consumerSecret) . '&';
        
        return base64_encode(hash_hmac('sha1', $baseString, $signingKey, true));
    }

    protected function generateNonce(): string
    {
        return md5(uniqid((string) rand(), true));
    }

    public function queryStatus(string $orderNumber): ?array
    {
        try {
            $params = [
                'oauth_consumer_key' => $this->consumerKey,
                'oauth_signature_method' => 'HMAC-SHA1',
                'oauth_timestamp' => time(),
                'oauth_nonce' => $this->generateNonce(),
                'oauth_version' => '1.0',
                'pesapal_merchant_reference' => $orderNumber
            ];

            $signature = $this->generateSignatureForQuery($params);
            $params['oauth_signature'] = $signature;

            $response = Http::asForm()
                ->timeout(30)
                ->post($this->queryUrl, $params);

            return $response->successful() ? $response->json() : null;

        } catch (\Exception $e) {
            Log::error('Pesapal Query Exception: ' . $e->getMessage());
            return null;
        }
    }

    protected function generateSignatureForQuery(array $params): string
    {
        $method = 'POST';
        $baseString = $method . '&' . rawurlencode($this->queryUrl) . '&';
        
        ksort($params);
        
        $paramString = '';
        foreach ($params as $key => $value) {
            if ($paramString !== '') {
                $paramString .= '&';
            }
            $paramString .= rawurlencode($key) . '=' . rawurlencode($value);
        }
        
        $baseString .= rawurlencode($paramString);
        $signingKey = rawurlencode($this->consumerSecret) . '&';
        
        return base64_encode(hash_hmac('sha1', $baseString, $signingKey, true));
    }

    public function isConfigured(): bool
    {
        return !empty($this->consumerKey) && !empty($this->consumerSecret);
    }
}