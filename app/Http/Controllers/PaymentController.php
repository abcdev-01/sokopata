<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function process(Order $order)
    {
        // Check if user is the buyer
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }

    return redirect()->route('pesapal.pay', $order);

    }

    public function simulateMpesaPayment(Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }
        
        // Simulate M-Pesa STK Push
        $transactionId = 'MPESA' . time() . rand(1000, 9999);
        
        $order->update([
            'payment_transaction_id' => $transactionId,
            'status' => 'payment_confirmed'
        ]);
        
        // Notify supplier (in production, send actual SMS)
        
        return redirect()->route('orders.show', $order)->with('success', 'Payment successful! Your order is being processed.');
    }
    public function confirmDelivery(Order $order)
{
    if ($order->buyer_id !== Auth::id()) {
        abort(403);
    }
    
    if ($order->status === 'delivered') {
        $order->payment_released = true;
        $order->payment_released_at = \Carbon\Carbon::now();
        $order->status = 'completed';
        $order->save();
        
        // Update supplier transaction count
        $supplier = $order->supplier;
        if ($supplier) {
            $supplier->increment('total_transactions');
        }
        
        return redirect()->back()->with('success', 'Delivery confirmed! Payment has been released to supplier.');
    }
    
    return redirect()->back()->with('error', 'Order not yet marked as delivered');
}
}