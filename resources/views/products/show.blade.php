@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">{{ $product->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($product->image)
                                <img src="{{ asset('storage/products/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}" style="width: 100%; object-fit: cover;">
                            @else
                                <div class="bg-light text-center py-5 rounded">
                                    <i class="fas fa-image fa-5x text-muted"></i>
                                    <p class="text-muted mt-2">No image available</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <span class="badge bg-success mb-2">{{ ucfirst($product->category) }}</span>
                                <h2 class="text-success">TZS {{ number_format($product->price) }}</h2>
                                <small class="text-muted">per {{ $product->unit }}</small>
                            </div>
                            
                            <div class="mb-3">
                                <p><strong>Supplier:</strong> {{ $product->supplier->business_name ?? $product->supplier->name }}</p>
                                <p><strong>Location:</strong> {{ $product->location }}</p>
                                <p><strong>Available Quantity:</strong> {{ $product->quantity }} {{ $product->unit }}</p>
                                <p><strong>Minimum Order:</strong> {{ $product->min_order_quantity }} {{ $product->unit }}</p>
                            </div>
                            
                            @auth
                                @if(auth()->user()->user_type == 'buyer')
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Quantity ({{ $product->unit }})</label>
                                        <input type="number" name="quantity" class="form-control" value="{{ $product->min_order_quantity }}" 
                                               min="{{ $product->min_order_quantity }}" max="{{ $product->quantity }}">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                    </button>
                                </form>
                                @elseif(auth()->user()->user_type == 'farmer' || auth()->user()->user_type == 'cooperative')
                                    <div class="alert alert-info">
                                        <i class="fas fa-store me-2"></i>
                                        You are a seller. You cannot buy your own products.
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-success w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login to Buy
                                </a>
                            @endauth
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Description</h5>
                        <p>{{ $product->description }}</p>
                    </div>

                    <!-- Reviews Section - Temporarily Disabled -->
                    @if(class_exists('App\Models\Review') && Schema::hasTable('reviews'))
                    <div class="card shadow mt-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-star me-2"></i>Customer Reviews</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-4 text-center">
                                    <h1 class="display-1 text-warning">{{ number_format($product->average_rating ?? 0, 1) }}</h1>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($product->average_rating ?? 0))
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-muted">Based on {{ $product->review_count ?? 0 }} reviews</p>
                                </div>
                            </div>
                            
                            @if(isset($product->reviews) && $product->reviews->count() > 0)
                                @foreach($product->reviews as $review)
                                    <div class="border-bottom pb-3 mb-3">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                                                <div>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star text-warning small"></i>
                                                        @else
                                                            <i class="far fa-star text-warning small"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mt-2 mb-0">{{ $review->comment }}</p>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center">No reviews yet. Be the first to review!</p>
                            @endif
                        </div>
                    </div>

                    <!-- Write Review Section -->
                    @auth
                    @if(auth()->user()->user_type == 'buyer')
                    <div class="card shadow mt-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-pen me-2"></i>Write a Review</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('/reviews') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="mb-3">
                                    <label class="form-label">Your Rating</label>
                                    <div class="rating-input">
                                        <i class="far fa-star fa-2x" data-rating="1"></i>
                                        <i class="far fa-star fa-2x" data-rating="2"></i>
                                        <i class="far fa-star fa-2x" data-rating="3"></i>
                                        <i class="far fa-star fa-2x" data-rating="4"></i>
                                        <i class="far fa-star fa-2x" data-rating="5"></i>
                                        <input type="hidden" name="rating" id="rating" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Your Review</label>
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience with this product..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Submit Review</button>
                            </form>
                        </div>
                    </div>
                    @endif
                    @endauth
                    @else
                    <!-- Reviews coming soon -->
                    <div class="card shadow mt-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-star me-2"></i>Customer Reviews</h5>
                        </div>
                        <div class="card-body text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Reviews feature coming soon!</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Why Buy from SokoPata?</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <i class="fas fa-shield-alt fa-2x text-success me-3"></i>
                        <div>
                            <strong>Secure Escrow Payments</strong>
                            <p class="small text-muted mb-0">Your money is safe until you confirm delivery</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-truck fa-2x text-success me-3"></i>
                        <div>
                            <strong>Fast Delivery</strong>
                            <p class="small text-muted mb-0">Track your order in real-time</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-star fa-2x text-success me-3"></i>
                        <div>
                            <strong>Verified Suppliers</strong>
                            <p class="small text-muted mb-0">All suppliers are KYC verified</p>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($relatedProducts->count() > 0)
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Related Products</h5>
                    </div>
                    <div class="card-body">
                        @foreach($relatedProducts as $related)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <div>
                                    <strong>{{ $related->name }}</strong>
                                    <br>
                                    <small class="text-success">TZS {{ number_format($related->price) }}/{{ $related->unit }}</small>
                                </div>
                                <a href="{{ route('products.show', $related) }}" class="btn btn-sm btn-outline-success">View</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .rating-input i {
        cursor: pointer;
        transition: color 0.2s;
    }
    .rating-input i:hover,
    .rating-input i.active {
        color: #ffc107;
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.rating-input i').forEach(star => {
        star.addEventListener('click', function() {
            let rating = this.dataset.rating;
            document.getElementById('rating').value = rating;
            document.querySelectorAll('.rating-input i').forEach(s => {
                s.classList.remove('fas');
                s.classList.add('far');
            });
            for(let i = 1; i <= rating; i++) {
                let starElement = document.querySelector(`.rating-input i[data-rating="${i}"]`);
                starElement.classList.remove('far');
                starElement.classList.add('fas');
            }
        });
    });
</script>
@endpush
@endsection