@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Product: {{ $product->name }}</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ url('/seller/product/' . $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ $product->description }}</textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price (TZS)</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $product->price }}">
                            </div>
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Quantity ({{ $product->unit }})</label>
                                <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" value="{{ $product->quantity }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="is_available" class="form-label">Status</label>
                            <select class="form-control" id="is_available" name="is_available">
                                <option value="1" {{ $product->is_available ? 'selected' : '' }}>Available</option>
                                <option value="0" {{ !$product->is_available ? 'selected' : '' }}>Unavailable</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            @if($product->image)
                                <div class="mb-2">
                                    <label class="text-muted">Current Image:</label>
                                    <img src="{{ asset('storage/products/' . $product->image) }}" style="max-width: 150px; max-height: 150px;" class="img-thumbnail d-block mt-1">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image. Max 2MB.</small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Update Product
                            </button>
                            <a href="{{ url('/seller/products') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection