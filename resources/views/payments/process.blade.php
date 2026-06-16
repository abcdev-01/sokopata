@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">Complete Payment</h4>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-phone-alt fa-4x text-success mb-3"></i>
                    <h5>Order #{{ $order->order_number }}</h5>
                    <h3 class="text-success mb-4">TZS {{ number_format($order->total_amount) }}</h3>
                    
                    <div class="alert alert-info">
                        <strong>Payment Method: {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</strong>
                    </div>
                    
                    @if($order->payment_method == 'mpesa')
                        <div class="payment-simulation">
                            <p>You will receive a prompt on your M-Pesa registered phone number:</p>
                            <p class="fw-bold">{{ auth()->user()->phone }}</p>
                            <p>Enter your M-Pesa PIN to complete the payment.</p>
                            <form action="{{ route('payment.mpesa', $order) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label>M-Pesa PIN</label>
                                    <input type="password" class="form-control text-center" placeholder="****" maxlength="4" required>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    Simulate M-Pesa Payment
                                </button>
                            </form>
                        </div>
                    @elseif($order->payment_method == 'tigo_pesa')
                        <div class="payment-simulation">
                            <p>You will receive a prompt on your Tigo Pesa registered phone number:</p>
                            <p class="fw-bold">{{ auth()->user()->phone }}</p>
                            <form action="{{ route('payment.mpesa', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    Simulate Tigo Pesa Payment
                                </button>
                            </form>
                        </div>
                    @elseif($order->payment_method == 'bank_transfer')
                        <div class="alert alert-warning">
                            <strong>Bank Transfer Details:</strong><br>
                            Bank: CRDB Bank Tanzania<br>
                            Account Name: SokoPata Limited<br>
                            Account Number: 0150234567800<br>
                            Reference: {{ $order->order_number }}
                        </div>
                        <form action="{{ route('payment.mpesa', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                Confirm Bank Transfer (Demo)
                            </button>
                        </form>
                    @endif
                    
                    <hr class="my-4">
                    
                    <div class="text-muted small">
                        <i class="fas fa-shield-alt"></i> Your payment is protected by SokoPata Escrow.<br>
                        Funds will only be released to the supplier after you confirm delivery.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection