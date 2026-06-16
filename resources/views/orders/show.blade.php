@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>
                            Order #{{ $order->order_number }}
                        </h5>
                        <span class="badge bg-light text-dark px-3 py-2">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Order Timeline -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <div class="text-center flex-grow-1">
                                <div class="rounded-circle bg-{{ $order->created_at ? 'success' : 'secondary' }} text-white d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div><small>Order Placed</small></div>
                                <div><small class="text-muted">{{ $order->created_at->format('d M') }}</small></div>
                            </div>
                            <div class="text-center flex-grow-1">
                                <div class="rounded-circle bg-{{ $order->status != 'pending' ? 'success' : 'secondary' }} text-white d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div><small>Payment</small></div>
                                <div><small class="text-muted">{{ $order->payment_transaction_id ? 'Confirmed' : 'Pending' }}</small></div>
                            </div>
                            <div class="text-center flex-grow-1">
                                <div class="rounded-circle bg-{{ in_array($order->status, ['processing', 'dispatched', 'delivered', 'completed']) ? 'success' : 'secondary' }} text-white d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div><small>Processing</small></div>
                            </div>
                            <div class="text-center flex-grow-1">
                                <div class="rounded-circle bg-{{ in_array($order->status, ['dispatched', 'delivered', 'completed']) ? 'success' : 'secondary' }} text-white d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div><small>Dispatched</small></div>
                            </div>
                            <div class="text-center flex-grow-1">
                                <div class="rounded-circle bg-{{ in_array($order->status, ['delivered', 'completed']) ? 'success' : 'secondary' }} text-white d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div><small>Delivered</small></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-info-circle me-2"></i>Order Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Order Date:</th>
                                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td>{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</td>
                                </tr>
                                <tr>
                                    <th>Transaction ID:</th>
                                    <td>{{ $order->payment_transaction_id ?? 'Pending' }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Status:</th>
                                    <td>
                                        @if($order->payment_released)
                                            <span class="badge bg-success">Released to Supplier</span>
                                        @elseif($order->payment_transaction_id)
                                            <span class="badge bg-info">Payment Confirmed (Escrow)</span>
                                        @else
                                            <span class="badge bg-warning">Pending Payment</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-map-marker-alt me-2"></i>Delivery Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $order->delivery_address }}</td>
                                </tr>
                                @if($order->delivered_at)
                                <tr>
                                    <th>Delivered:</th>
                                    <td>{{ $order->delivered_at->format('d M Y, H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                    <h6><i class="fas fa-shopping-cart me-2"></i>Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->product->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $item->product->location }}</small>
                                    </td>
                                    <td>{{ ucfirst($item->product->category) }}</td>
                                    <td>{{ $item->quantity }} {{ $item->product->unit }}</td>
                                    <td>TZS {{ number_format($item->unit_price) }}/{{ $item->product->unit }}</td>
                                    <td>TZS {{ number_format($item->total_price) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                    <td><strong>TZS {{ number_format($order->subtotal) }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end">Platform Fee (7%):</td>
                                    <td>TZS {{ number_format($order->commission) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end">Delivery Fee:</td>
                                    <td>TZS {{ number_format($order->delivery_fee) }}</td>
                                </tr>
                                <tr class="table-success">
                                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                    <td><strong class="h5">TZS {{ number_format($order->total_amount) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Action Cards -->
            @if(auth()->user()->user_type == 'buyer')
                @if($order->status == 'payment_pending')
                    <div class="card shadow mb-4">
                        <div class="card-header bg-warning">
                            <h6 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Required</h6>
                        </div>
                        <div class="card-body">
                            <p>Complete your payment to confirm this order.</p>
                            <a href="{{ route('payment.process', $order) }}" class="btn btn-success w-100">
                                <i class="fas fa-credit-card me-2"></i>Pay Now
                            </a>
                        </div>
                    </div>
                @endif
                
                @if($order->status == 'delivered' && !$order->payment_released)
                    <div class="card shadow mb-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Confirm Delivery</h6>
                        </div>
                        <div class="card-body">
                            <p>Have you received the order? Confirm to release payment to the supplier.</p>
                            <form action="{{ route('payment.confirm', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Confirm delivery and release payment?')">
                                    <i class="fas fa-check-circle me-2"></i>Confirm Delivery
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif
            
            @if(in_array(auth()->user()->user_type, ['farmer', 'cooperative']) && $order->supplier_id == auth()->id())
                @if(in_array($order->status, ['payment_confirmed', 'processing', 'dispatched']))
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-truck me-2"></i>Update Order Status</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('orders.status', $order) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Order Status</label>
                                    <select name="status" class="form-control">
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>🟡 Processing</option>
                                        <option value="dispatched" {{ $order->status == 'dispatched' ? 'selected' : '' }}>📦 Dispatched</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif
            
            <!-- Contact Support Card -->
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-headset me-2"></i>Need Help?</h6>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-phone-alt fa-3x text-muted mb-3"></i>
                    <p class="mb-2">Contact our support team:</p>
                    <p class="mb-1"><strong>📞 *150*50#</strong></p>
                    <p class="mb-3"><strong>📧 support@sokopata.co.tz</strong></p>
                    <button class="btn btn-outline-secondary btn-sm w-100" onclick="alert('Call *150*50# or email support@sokopata.co.tz for assistance')">
                        <i class="fas fa-envelope me-2"></i>Get Support
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection