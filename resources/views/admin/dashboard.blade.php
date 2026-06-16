@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-chart-line me-2"></i>Admin Dashboard</h2>
    
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5><i class="fas fa-users"></i> Total Users</h5>
                    <h2>{{ $totalUsers }}</h2>
                    <small>Farmers: {{ $totalFarmers }} | Buyers: {{ $totalBuyers }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5><i class="fas fa-boxes"></i> Products</h5>
                    <h2>{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h5><i class="fas fa-shopping-cart"></i> Orders</h5>
                    <h2>{{ $totalOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <h5><i class="fas fa-money-bill"></i> Revenue</h5>
                    <h2>TZS {{ number_format($totalRevenue) }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-danger text-white">
                    <h5><i class="fas fa-clock"></i> Recent Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Order #</th><th>Buyer</th><th>Amount</th><th>Status</th><th>Action</th></tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>{{ substr($order->order_number, 0, 8) }}...</td>
                                    <td>{{ $order->buyer->name ?? 'N/A' }}</td>
                                    <td>TZS {{ number_format($order->total_amount) }}</td>
                                    <td><span class="badge bg-warning">{{ $order->status }}</span></td>
                                    <td><a href="{{ route('admin.orders.view', $order->id) }}" class="btn btn-sm btn-primary">View</a></td>
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
                    <h5><i class="fas fa-user-plus"></i> Recent Users</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Name</th><th>Email</th><th>Type</th><th>Action</th></tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($user->user_type) }}</span></td>
                                    <td><a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection