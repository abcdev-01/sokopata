@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-chart-line me-2"></i>Seller Dashboard</h2>
    
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2 class="mb-0">{{ $products ?? 0 }}</h2>
                    <small>{{ $activeProducts ?? 0 }} active</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Sales Value</h5>
                    <h2 class="mb-0">TZS {{ number_format($totalSales ?? 0) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Orders</h5>
                    <h2 class="mb-0">{{ $pendingOrders ?? 0 }}</h2>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <h2 class="mb-0">{{ $completedOrders ?? 0 }}</h2>
                    <small>Orders</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Orders</h5>
                </div>
                <div class="card-body">
                    @if(isset($recentOrders) && $recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Buyer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>{{ substr($order->order_number, 0, 10) }}...</td>
                                        <td>{{ $order->buyer->name ?? 'N/A' }}</td>
                                        <td>TZS {{ number_format($order->total_amount) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ url('/orders/' . $order->id) }}" class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No orders yet</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ url('/add-product') }}" class="btn btn-success w-100 mb-2">
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
            
            <div class="card shadow mt-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Add clear product images</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Set competitive prices</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Update stock regularly</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Respond to orders quickly</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection