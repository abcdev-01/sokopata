@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-heart text-danger me-2"></i>My Wishlist</h2>
    
    @if($wishlists->count() > 0)
        <div class="row">
            @foreach($wishlists as $item)
                <div class="col-md-3 mb-4">
                    <div class="card shadow h-100">
                        @if($item->product->image)
                            <img src="{{ asset('storage/products/' . $item->product->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-light text-center py-4">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->product->name }}</h5>
                            <p class="text-success h5">TZS {{ number_format($item->product->price) }}/{{ $item->product->unit }}</p>
                            <p class="small text-muted">{{ Str::limit($item->product->description, 60) }}</p>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-grid gap-2">
                                <a href="{{ url('/products/' . $item->product->id) }}" class="btn btn-outline-primary btn-sm">View Product</a>
                                <form action="{{ url('/wishlist/remove/' . $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-heart-broken fa-4x text-muted mb-3"></i>
                <h4>Your wishlist is empty</h4>
                <p class="text-muted">Save your favorite products here!</p>
                <a href="{{ url('/products') }}" class="btn btn-success">Browse Products</a>
            </div>
        </div>
    @endif
</div>
@endsection