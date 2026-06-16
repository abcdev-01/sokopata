@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger">
                <i class="fas fa-crown me-2"></i>
                <strong>Admin Dashboard</strong> - Platform Overview & Management
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <h2 class="mb-0">{{ $totalUsers }}</h2>
                    <small>{{ $verifiedUsers }} verified | {{ $newUsersToday }} new today</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Suppliers</h5>
                    <h2 class="mb-0">{{ $totalSuppliers }}</h2>
                    <small>Farmers & Cooperatives</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Buyers</h5>
                    <h2 class="mb-0">{{ $totalBuyers }}</h2>
                    <small>Restaurants & Hotels</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <h2 class="mb-0">TZS {{ number_format($totalRevenue) }}</h2>
                    <small>Platform Commission</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-dark text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Products</h5>
                    <h2 class="mb-0">{{ $totalProducts }}</h2>
                    <small>{{ $activeProducts }} active | {{ $outOfStock }} out of stock</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-secondary text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Orders</h5>
                    <h2 class="mb-0">{{ $totalOrders }}</h2>
                    <small>{{ $completedOrders }} completed | {{ $pendingOrders }} pending</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Recent Users</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Name</th><th>Email</th><th>Type</th><th>Joined</th></tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($user->user_type) }}</span></td>
                                    <td>{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Recent Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Order #</th><th>Buyer</th><th>Amount</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>{{ substr($order->order_number, 0, 8) }}...</td>
                                    <td>{{ $order->buyer->name ?? 'N/A' }}</td>
                                    <td>TZS {{ number_format($order->total_amount) }}</td>
                                    <td><span class="badge bg-warning">{{ $order->status }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Top Suppliers</h5>
                </div>
                <div class="card-body">
                    @foreach($topSuppliers as $supplier)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $supplier->business_name ?? $supplier->name }}</span>
                            <span class="badge bg-success">{{ $supplier->products_count }} products</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Top Buyers</h5>
                </div>
                <div class="card-body">
                    @foreach($topBuyers as $buyer)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $buyer->business_name ?? $buyer->name }}</span>
                            <span class="badge bg-info">{{ $buyer->orders_as_buyer_count }} orders</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection