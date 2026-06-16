@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4"><i class="fas fa-credit-card me-2"></i>Checkout</h2>
        </div>
    </div>

    <div class="row">
        <!-- Order Items -->
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Order Summary</h5>
                </div>
                <div class="card-body">
                    @foreach($products as $product)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <h6 class="mb-0">{{ $product->name }}</h6>
                                <small class="text-muted">{{ $product->supplier->business_name ?? $product->supplier->name }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-secondary">{{ $cart[$product->id] }} {{ $product->unit }}</span>
                                <div class="text-success fw-bold">TZS {{ number_format($product->price * $cart[$product->id]) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Delivery Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Delivery Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('delivery_address') is-invalid @enderror" 
                                      id="delivery_address" name="delivery_address" rows="3" required 
                                      placeholder="Enter your full delivery address">{{ old('delivery_address', auth()->user()->address ?? '') }}</textarea>
                            @error('delivery_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="city" class="form-label">City</label>
                                <select class="form-control" id="city" name="city">
                                    <option value="Dar es Salaam">Dar es Salaam</option>
                                    <option value="Arusha">Arusha</option>
                                    <option value="Mwanza">Mwanza</option>
                                    <option value="Dodoma">Dodoma</option>
                                    <option value="Tanga">Tanga</option>
                                    <option value="Mbeya">Mbeya</option>
                                    <option value="Morogoro">Morogoro</option>
                                    <option value="Zanzibar">Zanzibar</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="delivery_date" class="form-label">Preferred Delivery Date</label>
                                <input type="date" class="form-control" id="delivery_date" name="delivery_date" 
                                       min="{{ date('Y-m-d', strtotime('+2 days')) }}">
                                <small class="text-muted">Minimum 2 days from today</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Delivery Instructions (Optional)</label>
                            <textarea class="form-control" name="delivery_instructions" rows="2" 
                                      placeholder="Any special delivery instructions..."></textarea>
                        </div>

                        <!-- Payment Method - FIXED -->
                        <div class="mb-3">
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="border rounded p-3 text-center payment-method selected" data-method="pesapal">
                                        <input type="radio" name="payment_method" value="pesapal" checked required>
                                        <i class="fas fa-credit-card fa-2x d-block text-success"></i>
                                        <strong>Pesapal</strong>
                                        <small class="d-block text-muted">M-Pesa, Tigo, Airtel</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3 text-center payment-method" data-method="bank_transfer">
                                        <input type="radio" name="payment_method" value="bank_transfer">
                                        <i class="fas fa-university fa-2x d-block text-primary"></i>
                                        <strong>Bank Transfer</strong>
                                        <small class="d-block text-muted">Direct Bank Transfer</small>
                                    </div>
                                </div>
                            </div>
                            @error('payment_method')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary & Payment -->
        <div class="col-md-5">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Items ({{ count($products) }})</h6>
                        @foreach($products as $product)
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ $product->name }} × {{ $cart[$product->id] }}</span>
                                <span>TZS {{ number_format($product->price * $cart[$product->id]) }}</span>
                            </div>
                        @endforeach
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>TZS {{ number_format($subtotal) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Platform Fee (7%)</span>
                        <span>TZS {{ number_format($commission) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Delivery Fee</span>
                        <span>TZS {{ number_format($deliveryFee) }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total to Pay</strong>
                        <strong class="text-success h4">TZS {{ number_format($total) }}</strong>
                    </div>

                    <div class="alert alert-info small mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Your payment is secure. Choose Pesapal for mobile money payments.
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" form="checkoutForm" class="btn btn-success btn-lg">
                            <i class="fas fa-credit-card me-2"></i>Place Order & Pay
                        </button>
                        <a href="{{ route('cart') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .payment-method {
        cursor: pointer;
        transition: all 0.3s;
        background: #fff;
    }
    .payment-method:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
    }
    .payment-method.selected {
        border-color: #28a745 !important;
        background: #d4edda;
    }
    .payment-method input[type="radio"] {
        position: absolute;
        opacity: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.payment-method').forEach(function(method) {
        method.addEventListener('click', function() {
            // Remove selected class from all
            document.querySelectorAll('.payment-method').forEach(function(m) {
                m.classList.remove('selected');
            });
            // Add selected class to clicked
            this.classList.add('selected');
            // Check the radio button
            var radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
            }
        });
    });
</script>
@endpush
@endsection