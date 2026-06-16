@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i>Complete Payment</h4>
                </div>
                <div class="card-body">
                    @if(env('PESAPAL_SIMULATION_MODE', true))
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Simulation Mode Active</strong> - Payments will be simulated for testing.
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="bg-light p-3 rounded-circle d-inline-block mb-3">
                            <i class="fas fa-credit-card fa-3x text-success"></i>
                        </div>
                        <h5>Pay securely with Pesapal</h5>
                        <p class="text-muted small">Supported: M-Pesa, Tigo Pesa, Airtel Money, Bank Transfer</p>
                    </div>

                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-6">
                                <strong>Order #</strong><br>
                                {{ $order->order_number }}
                            </div>
                            <div class="col-6 text-end">
                                <strong>Amount</strong><br>
                                <span class="text-success h5">TZS {{ number_format($order->total_amount) }}</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('pesapal.initiate', $order) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                   id="phone_number" name="phone_number" 
                                   placeholder="e.g., 0712345678" value="{{ old('phone_number', auth()->user()->phone) }}" required>
                            <small class="text-muted">Enter your phone number for payment confirmation</small>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            <small class="text-muted">Payment receipt will be sent to this email</small>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning small">
                            <i class="fas fa-shield-alt me-2"></i>
                            @if(env('PESAPAL_SIMULATION_MODE', true))
                                <strong>SIMULATION:</strong> You will be redirected to a success page.
                            @else
                                You will be redirected to Pesapal secure payment page.
                            @endif
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-credit-card me-2"></i>Pay with Pesapal
                        </button>

                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </form>

                    <div class="mt-4">
                        <div class="row text-center">
                            <div class="col-3">
                                <i class="fas fa-lock text-success"></i>
                                <small class="d-block text-muted">Secure</small>
                            </div>
                            <div class="col-3">
                                <i class="fas fa-bolt text-success"></i>
                                <small class="d-block text-muted">Fast</small>
                            </div>
                            <div class="col-3">
                                <i class="fas fa-check-circle text-success"></i>
                                <small class="d-block text-muted">Reliable</small>
                            </div>
                            <div class="col-3">
                                <i class="fas fa-phone text-success"></i>
                                <small class="d-block text-muted">24/7 Support</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection