@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-shopping-cart me-2"></i>
                <strong>Buyer Dashboard</strong> - Track your orders and discover fresh produce
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Spent</h5>
                    <h2 class="mb-0">TZS {{ number_format($totalSpent) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <h2 class="mb-0">{{ $totalOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <h2 class="mb-0">{{ $completedOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Processing</h5>
                    <h2 class="mb-0">{{ $processingOrders ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Orders</h5>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr><th>Order #</th><th>Supplier</th><th>Amount</th><th>Status</th><th>Action</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>{{ substr($order->order_number, 0, 10) }}...</td>
                                        <td>{{ $order->supplier->business_name ?? $order->supplier->name }}</td>
                                        <td>TZS {{ number_format($order->total_amount) }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'secondary',
                                                    'payment_pending' => 'warning',
                                                    'payment_confirmed' => 'info',
                                                    'processing' => 'primary',
                                                    'dispatched' => 'info',
                                                    'delivered' => 'success',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                $color = $statusColors[$order->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td><a href="{{ url('/orders/' . $order->id) }}" class="btn btn-sm btn-primary">View</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            }</table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No orders yet. Start shopping!</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ url('/products') }}" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-shopping-cart me-2"></i>Browse Products
                    </a>
                    <a href="{{ url('/cart') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-shopping-bag me-2"></i>View Cart
                    </a>
                    <a href="{{ url('/orders') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-list me-2"></i>All Orders
                    </a>
                </div>
            </div>
            
            @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Recommended for You</h5>
                </div>
                <div class="card-body">
                    @foreach($recommendedProducts as $product)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <strong>{{ $product->name }}</strong><br>
                                <small class="text-success">TZS {{ number_format($product->price) }}/{{ $product->unit }}</small>
                            </div>
                            <a href="{{ url('/products/' . $product->id) }}" class="btn btn-sm btn-outline-success">View</a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection