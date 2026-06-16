@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-store me-2"></i>
                <strong>Seller Dashboard</strong> - Manage your products and track orders
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2 class="mb-0">{{ $products }}</h2>
                    <small>{{ $activeProducts }} active</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Sales</h5>
                    <h2 class="mb-0">TZS {{ number_format($totalSales) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Pending Orders</h5>
                    <h2 class="mb-0">{{ $pendingOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Completed Orders</h5>
                    <h2 class="mb-0">{{ $completedOrders }}</h2>
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
                    @if(isset($recentOrders) && $recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr><th>Order #</th><th>Buyer</th><th>Amount</th><th>Status</th><th>Action</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>{{ substr($order->order_number, 0, 10) }}...</td>
                                        <td>{{ $order->buyer->name ?? 'N/A' }}</td>
                                        <td>TZS {{ number_format($order->total_amount) }}</td>
                                        <td><span class="badge bg-warning">{{ ucfirst($order->status) }}</span></td>
                                        <td><a href="{{ url('/orders/' . $order->id) }}" class="btn btn-sm btn-primary">View</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            }</table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No orders yet</p>
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
                    <a href="{{ url('/seller/add-product') }}" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-plus-circle me-2"></i>Add New Product
                    </a>
                    <a href="{{ url('/seller/products') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-list me-2"></i>Manage Products
                    </a>
                    <a href="{{ url('/products') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-store me-2"></i>View Marketplace
                    </a>
                </div>
            </div>
            
            @if(isset($recentProducts) && $recentProducts->count() > 0)
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Recent Products</h5>
                </div>
                <div class="card-body">
                    @foreach($recentProducts as $product)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <strong>{{ $product->name }}</strong><br>
                                <small>TZS {{ number_format($product->price) }}/{{ $product->unit }}</small>
                            </div>
                            <span class="badge bg-{{ $product->is_available ? 'success' : 'danger' }}">
                                {{ $product->is_available ? 'Available' : 'Out' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection