@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Orders</h2>
        <a href="{{ route('products.index') }}" class="btn btn-success">
            <i class="fas fa-shopping-cart me-2"></i>Continue Shopping
        </a>
    </div>
    
    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-{{ 
                            $order->status === 'completed' ? 'success' : 
                            ($order->status === 'cancelled' ? 'danger' : 
                            ($order->status === 'delivered' ? 'info' : 
                            ($order->status === 'dispatched' ? 'primary' : 'warning'))) 
                        }} text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>
                                    <i class="fas fa-receipt me-2"></i>
                                    Order #{{ substr($order->order_number, 0, 15) }}...
                                </strong>
                                <span class="badge bg-light text-dark px-3 py-2">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Order Date:</small>
                                    <p class="mb-0">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Total Amount:</small>
                                    <h5 class="text-success mb-0">TZS {{ number_format($order->total_amount) }}</h5>
                                </div>
                            </div>
                            
                            @if(auth()->user()->user_type == 'buyer')
                                <div class="mb-3">
                                    <small class="text-muted">Supplier:</small>
                                    <p class="mb-0">
                                        <strong>{{ $order->supplier->business_name ?? $order->supplier->name }}</strong>
                                    </p>
                                </div>
                            @else
                                <div class="mb-3">
                                    <small class="text-muted">Buyer:</small>
                                    <p class="mb-0">
                                        <strong>{{ $order->buyer->business_name ?? $order->buyer->name }}</strong>
                                    </p>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <small class="text-muted">Items ({{ $order->items->count() }}):</small>
                                <div class="small">
                                    @foreach($order->items->take(2) as $item)
                                        <div>{{ $item->quantity }} x {{ $item->product->name }}</div>
                                    @endforeach
                                    @if($order->items->count() > 2)
                                        <div class="text-muted">+ {{ $order->items->count() - 2 }} more items</div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary btn-sm flex-grow-1">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                                @if($order->status == 'payment_pending' && auth()->user()->user_type == 'buyer')
                                    <a href="{{ route('payment.process', $order) }}" class="btn btn-success btn-sm flex-grow-1">
                                        <i class="fas fa-credit-card me-2"></i>Pay Now
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                <h4>No Orders Yet</h4>
                <p class="text-muted">You haven't placed any orders yet.</p>
                <a href="{{ route('products.index') }}" class="btn btn-success mt-3">
                    <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                </a>
            </div>
        </div>
    @endif
</div>
@endsection