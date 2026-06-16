<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * @var object|null
     */
    protected $client;
    
    /**
     * @var object|null
     */
    protected $sms;
    
    /**
     * @var string
     */
    protected $username;
    
    /**
     * @var string|null
     */
    protected $apiKey;

    public function __construct()
    {
        $this->username = env('AT_USERNAME', 'sandbox');
        $this->apiKey = env('AT_API_KEY');
        
        // Only initialize if API key exists
        if ($this->apiKey && class_exists('AfricasTalking\SDK\AfricasTalking')) {
            try {
                $this->client = new \AfricasTalking\SDK\AfricasTalking($this->username, $this->apiKey);
                $this->sms = $this->client->sms();
            } catch (\Exception $e) {
                Log::warning('Africa\'s Talking initialization failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Send SMS to a single recipient
     */
    public function send(string $phoneNumber, string $message): bool
    {
        if (!$this->sms) {
            Log::warning('SMS service not configured. Skipping SMS send.');
            return false;
        }

        // Format phone number to international format
        $phoneNumber = preg_replace('/^0/', '254', $phoneNumber);
        $phoneNumber = preg_replace('/^\+/', '', $phoneNumber);
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        try {
            $result = $this->sms->send([
                'to' => $phoneNumber,
                'message' => $message,
                'from' => env('AT_SENDER_ID', 'SokoPata')
            ]);

            if ($result && isset($result['status']) && $result['status'] === 'success') {
                Log::info("SMS sent to {$phoneNumber}: {$message}");
                return true;
            }

            Log::error('SMS failed: ' . json_encode($result));
            return false;

        } catch (\Exception $e) {
            Log::error('SMS Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send order confirmation to buyer
     */
    public function sendOrderConfirmation(Order $order): bool
    {
        $message = "SokoPata: Order #{$order->order_number} confirmed! Amount: TZS " . 
                   number_format($order->total_amount) . ". Track your order in the app.";
        
        return $this->send($order->buyer->phone, $message);
    }

    /**
     * Send new order notification to supplier
     */
    public function sendNewOrderNotification(Order $order): bool
    {
        $message = "SokoPata: New order #{$order->order_number} received! " .
                   "Buyer: {$order->buyer->name}. Total: TZS " . number_format($order->total_amount);
        
        return $this->send($order->supplier->phone, $message);
    }

    /**
     * Send delivery confirmation request to buyer
     */
    public function sendDeliveryConfirmation(Order $order): bool
    {
        $message = "SokoPata: Order #{$order->order_number} has been dispatched! " .
                   "Please confirm delivery when you receive it.";
        
        return $this->send($order->buyer->phone, $message);
    }
}