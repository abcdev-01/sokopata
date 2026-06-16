<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MpesaService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    protected MpesaService $mpesa;
    protected SmsService $sms;

    public function __construct(MpesaService $mpesa, SmsService $sms)
    {
        $this->mpesa = $mpesa;
        $this->sms = $sms;
        $this->middleware('auth')->except(['callback']);
    }

    public function initiatePayment(Request $request, Order $order)
    {
        // Check if order belongs to user
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'phone_number' => 'required|regex:/^([0-9]{10,12})$/'
        ]);

        $response = $this->mpesa->stkPush(
            $request->phone_number,
            $order->total_amount,
            $order->order_number,
            'SokoPata Payment'
        );

        if (isset($response['error'])) {
            return back()->with('error', 'Payment initiation failed: ' . $response['error']);
        }

        // Store checkout request ID
        $order->mpesa_checkout_request_id = $response['CheckoutRequestID'];
        $order->save();

        return view('payments.waiting', [
            'order' => $order,
            'checkoutRequestId' => $response['CheckoutRequestID']
        ]);
    }

    public function callback(Request $request)
    {
        Log::info('M-Pesa Callback Received', $request->all());

        $data = $request->all();

        if (isset($data['Body']['stkCallback'])) {
            $callback = $data['Body']['stkCallback'];
            
            $resultCode = $callback['ResultCode'];
            $checkoutRequestId = $callback['CheckoutRequestID'];
            
            $order = Order::where('mpesa_checkout_request_id', $checkoutRequestId)->first();
            
            if ($order) {
                if ($resultCode == 0) {
                    $order->update([
                        'status' => 'payment_confirmed',
                        'payment_transaction_id' => $callback['CallbackMetadata']['Item'][1]['Value'] ?? null,
                        'mpesa_result_code' => $resultCode
                    ]);
                    
                    // Send SMS notifications
                    $this->sms->sendOrderConfirmation($order);
                    $this->sms->sendNewOrderNotification($order);
                    
                } else {
                    $order->update([
                        'status' => 'payment_failed',
                        'mpesa_result_code' => $resultCode,
                        'mpesa_result_desc' => $callback['ResultDesc'] ?? 'Payment failed'
                    ]);
                }
            }
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }

    public function checkStatus(int $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        if ($order->mpesa_checkout_request_id) {
            $result = $this->mpesa->queryStatus($order->mpesa_checkout_request_id);
            return response()->json($result);
        }
        
        return response()->json(['error' => 'No transaction found'], 404);
    }
}