@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow">
                <div class="card-body py-5">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h1 class="display-1 text-muted">404</h1>
                    <h3 class="mb-3">Page Not Found</h3>
                    <p class="text-muted mb-4">Sorry, the page you are looking for does not exist.</p>
                    <a href="{{ url('/') }}" class="btn btn-success">
                        <i class="fas fa-home me-2"></i>Go Home
                    </a>
                    <a href="{{ url('/products') }}" class="btn btn-outline-primary">
                        <i class="fas fa-store me-2"></i>Browse Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection