<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PesapalService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PesapalController extends Controller
{
    protected PesapalService $pesapal;
    protected SmsService $sms;

    public function __construct(PesapalService $pesapal, SmsService $sms)
    {
        $this->pesapal = $pesapal;
        $this->sms = $sms;
        $this->middleware('auth')->except(['ipn', 'callback', 'simulateSuccess']);
    }

    public function showPaymentForm(Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }

        return view('payments.pesapal', compact('order'));
    }

    public function initiatePayment(Request $request, Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'phone_number' => 'required|regex:/^([0-9]{10,12})$/',
            'email' => 'required|email'
        ]);

        $response = $this->pesapal->submitOrder(
            $order,
            (float) $order->total_amount,
            $request->phone_number,
            $request->email
        );

        if (!$response['success']) {
            return back()->with('error', 'Payment initiation failed: ' . ($response['error'] ?? 'Unknown error'));
        }

        if (isset($response['transaction_tracking_id'])) {
            $order->pesapal_transaction_tracking_id = $response['transaction_tracking_id'];
            $order->save();
        }

        if (isset($response['redirect_url'])) {
            return redirect($response['redirect_url']);
        }

        return back()->with('error', 'Unable to redirect to payment page');
    }

    public function simulateSuccess(Order $order)
    {
        $order->update([
            'status' => 'payment_confirmed',
            'payment_transaction_id' => 'SIM_' . Str::random(10),
            'payment_released' => false
        ]);

        $this->sms->sendOrderConfirmation($order);
        $this->sms->sendNewOrderNotification($order);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Payment successful! (Simulation Mode) Your order is being processed.');
    }

    public function callback(Request $request)
    {
        Log::info('Pesapal Callback:', $request->all());

        $orderId = $request->input('pesapal_merchant_reference');
        $status = $request->input('pesapal_payment_status_type');

        $order = Order::where('order_number', $orderId)->first();

        if ($order) {
            if ($status === 'COMPLETED') {
                $order->update([
                    'status' => 'payment_confirmed',
                    'payment_released' => false,
                    'pesapal_payment_status' => $status
                ]);

                $this->sms->sendOrderConfirmation($order);
                $this->sms->sendNewOrderNotification($order);

                return redirect()->route('orders.show', $order)
                    ->with('success', 'Payment successful! Your order is being processed.');
            } else {
                $order->update([
                    'status' => 'payment_failed',
                    'pesapal_payment_status' => $status,
                    'cancellation_reason' => 'Payment failed or cancelled'
                ]);

                return redirect()->route('orders.show', $order)
                    ->with('error', 'Payment failed. Please try again.');
            }
        }

        return redirect()->route('home')->with('error', 'Order not found.');
    }

    public function ipn(Request $request)
    {
        Log::info('Pesapal IPN Received:', $request->all());

        $orderId = $request->input('pesapal_merchant_reference');
        $status = $request->input('pesapal_payment_status_type');

        $order = Order::where('order_number', $orderId)->first();

        if ($order) {
            if ($status === 'COMPLETED') {
                $order->update([
                    'status' => 'payment_confirmed',
                    'pesapal_transaction_tracking_id' => $request->input('pesapal_transaction_tracking_id'),
                    'pesapal_payment_status' => $status,
                    'payment_transaction_id' => $request->input('pesapal_transaction_tracking_id'),
                    'payment_released' => false
                ]);

                $this->sms->sendOrderConfirmation($order);
                $this->sms->sendNewOrderNotification($order);

            } elseif ($status === 'FAILED') {
                $order->update([
                    'status' => 'payment_failed',
                    'pesapal_payment_status' => $status
                ]);
            }
        }

        return response('OK', 200);
    }

    public function checkStatus(Order $order)
    {
        $result = $this->pesapal->queryStatus($order->order_number);
        return response()->json($result);
    }
}