@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Products</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ url('/products') }}">
                        <!-- Search -->
                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Product name...">
                        </div>
                        
                        <!-- Category -->
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                <option value="vegetables" {{ request('category') == 'vegetables' ? 'selected' : '' }}>Vegetables</option>
                                <option value="fruits" {{ request('category') == 'fruits' ? 'selected' : '' }}>Fruits</option>
                                <option value="grains" {{ request('category') == 'grains' ? 'selected' : '' }}>Grains</option>
                                <option value="dairy" {{ request('category') == 'dairy' ? 'selected' : '' }}>Dairy</option>
                                <option value="meat" {{ request('category') == 'meat' ? 'selected' : '' }}>Meat</option>
                                <option value="fish" {{ request('category') == 'fish' ? 'selected' : '' }}>Fish</option>
                                <option value="processed" {{ request('category') == 'processed' ? 'selected' : '' }}>Processed</option>
                            </select>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label">Price Range (TZS)</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Location -->
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <select name="location" class="form-select">
                                <option value="">All Locations</option>
                                <option value="Dar es Salaam" {{ request('location') == 'Dar es Salaam' ? 'selected' : '' }}>Dar es Salaam</option>
                                <option value="Arusha" {{ request('location') == 'Arusha' ? 'selected' : '' }}>Arusha</option>
                                <option value="Mwanza" {{ request('location') == 'Mwanza' ? 'selected' : '' }}>Mwanza</option>
                                <option value="Dodoma" {{ request('location') == 'Dodoma' ? 'selected' : '' }}>Dodoma</option>
                                <option value="Tanga" {{ request('location') == 'Tanga' ? 'selected' : '' }}>Tanga</option>
                            </select>
                        </div>
                        
                        <!-- Sort By -->
                        <div class="mb-3">
                            <label class="form-label">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-search me-2"></i>Apply Filters
                        </button>
                        
                        @if(request()->anyFilled(['search', 'category', 'min_price', 'max_price', 'location', 'sort']))
                            <a href="{{ url('/products') }}" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="fas fa-times me-2"></i>Clear All
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Marketplace</h2>
                <div class="text-muted">{{ $products->total() }} products found</div>
            </div>
            
            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow h-100 product-card">
                                <!-- Wishlist Button (Top Right Corner) -->
                                @auth
                                    @if(auth()->user()->user_type == 'buyer')
                                        <form action="{{ url('/wishlist/add/' . $product->id) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                                            @csrf
                                            <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm" style="width: 35px; height: 35px;">
                                                <i class="far fa-heart text-danger"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                                
                                @if($product->image)
                                    <img src="{{ asset('storage/products/' . $product->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $product->name }}">
                                @else
                                    <div class="bg-light text-center py-5" style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                        <p class="text-muted mb-0 mt-2">No Image</p>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $product->name }}</h5>
                                        <span class="badge bg-success">{{ ucfirst($product->category) }}</span>
                                    </div>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-store me-1"></i>{{ $product->supplier->business_name ?? $product->supplier->name }}
                                    </p>
                                    <p class="card-text small">{{ Str::limit($product->description, 80) }}</p>
                                    <div class="mb-2">
                                        <strong class="text-success h5">TZS {{ number_format($product->price) }}</strong>
                                        <small>/{{ $product->unit }}</small>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $product->location }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-boxes me-1"></i>{{ $product->quantity }} {{ $product->unit }} available
                                        </small>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <a href="{{ url('/products/' . $product->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                        @auth
                                            @if(auth()->user()->user_type == 'buyer')
                                            <form action="{{ url('/cart/add/' . $product->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm w-100">
                                                    <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                                </button>
                                            </form>
                                            @endif
                                        @else
                                            <a href="{{ url('/login') }}" class="btn btn-success btn-sm w-100">
                                                <i class="fas fa-cart-plus me-1"></i>Login to Buy
                                            </a>
                                        @endauth
                                    </div>
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
                        <i class="fas fa-search fa-4x text-muted mb-3"></i>
                        <h4>No Products Found</h4>
                        <p class="text-muted">Try adjusting your filters or check back later for new products.</p>
                        <a href="{{ url('/products') }}" class="btn btn-success">
                            <i class="fas fa-sync-alt me-2"></i>View All Products
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .product-card {
        transition: transform 0.3s;
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-5px);
    }
    .product-card .btn-light {
        transition: all 0.3s;
    }
    .product-card .btn-light:hover {
        background-color: #ffc107;
        border-color: #ffc107;
    }
    .product-card .btn-light:hover i {
        color: #dc3545 !important;
    }
</style>
@endpush
@endsection