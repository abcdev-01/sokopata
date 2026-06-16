@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Shopping Cart</h2>
    
    @if($products->count() > 0)
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <strong>{{ $product->name }}</strong><br>
                                            <small class="text-muted">{{ ucfirst($product->category) }} | {{ $product->supplier->business_name ?? $product->supplier->name }}</small>
                                        </td>
                                        <td>TZS {{ number_format($product->price) }}/{{ $product->unit }}</td>
                                        <td>
                                            <form action="{{ route('cart.update', $product) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="quantity" value="{{ $cart[$product->id] }}" 
                                                       min="1" max="{{ $product->quantity }}" style="width: 70px;" class="form-control d-inline">
                                                <button type="submit" class="btn btn-sm btn-secondary">Update</button>
                                            </form>
                                        </td>
                                        <td>TZS {{ number_format($product->price * $cart[$product->id]) }}</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $product) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th colspan="2">TZS {{ number_format($total) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>TZS {{ number_format($total) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Platform Fee (7%):</span>
                            <strong>TZS {{ number_format($total * 0.07) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee:</span>
                            <strong>TZS 5,000</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total:</span>
                            <strong class="text-success">TZS {{ number_format($total + ($total * 0.07) + 5000) }}</strong>
                        </div>
                        <a href="{{ route('checkout') }}" class="btn btn-success w-100">
                            Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
            <h4>Your cart is empty</h4>
            <p>Browse our marketplace and add some products to your cart.</p>
            <a href="{{ route('products.index') }}" class="btn btn-success">Start Shopping</a>
        </div>
    @endif
</div>
@endsection