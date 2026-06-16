@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-boxes me-2"></i>My Products</h2>
        <a href="{{ url('/add-product') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-2"></i>Add New Product
        </a>
    </div>
    
    @if(isset($products) && $products->count() > 0)
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card shadow h-100">
                        @if($product->image)
                            <img src="{{ asset('storage/products/' . $product->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $product->name }}">
                        @else
                            <div class="bg-light text-center py-5" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                                <p class="text-muted mb-0 mt-2">No Image</p>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="text-muted small">
                                <i class="fas fa-tag me-1"></i>{{ ucfirst($product->category) }}
                            </p>
                            <p class="card-text small">{{ Str::limit($product->description, 80) }}</p>
                            <div class="mb-2">
                                <strong class="text-success h5">TZS {{ number_format($product->price) }}</strong>
                                <small>/{{ $product->unit }}</small>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-boxes me-1"></i>Stock: {{ $product->quantity }} {{ $product->unit }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $product->location }}
                                </small>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-{{ $product->is_available ? 'success' : 'danger' }}">
                                    {{ $product->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="btn-group w-100">
                                <a href="{{ url('/products/' . $product->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ url('/seller/product/' . $product->id . '/edit') }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $product->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                            
                            <form id="delete-form-{{ $product->id }}" action="{{ url('/seller/product/' . $product->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h4>No Products Yet</h4>
                <p class="text-muted">You haven't listed any products yet.</p>
                <a href="{{ url('/add-product') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle me-2"></i>Add Your First Product
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Wait for the DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Get all delete buttons
        var deleteButtons = document.querySelectorAll('.delete-btn');
        
        // Add click event to each button
        for (var i = 0; i < deleteButtons.length; i++) {
            deleteButtons[i].addEventListener('click', function() {
                var productId = this.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                    document.getElementById('delete-form-' + productId).submit();
                }
            });
        }
    });
</script>
@endpush