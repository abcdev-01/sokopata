@extends('layouts.app')

@section('content')
@if(session('message'))
<div class="alert alert-info alert-dismissible fade show text-center" role="alert">
    <i class="fas fa-info-circle me-2"></i>{{ session('message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Rest of your landing page content -->
<div class="bg-success text-white py-5">
    <div class="container text-center py-5">
        <h1 class="display-3 fw-bold">From Farm to Fork, Seamlessly.</h1>
        <p class="lead fs-3 my-4">Tanzania's first dedicated B2B online marketplace connecting farmers directly with restaurants, hotels, and caterers.</p>
        <div class="mt-4">
            <a href="{{ url('/login') }}" class="btn btn-light btn-lg text-success mx-2">Get Started</a>
            <a href="{{ url('/products') }}" class="btn btn-outline-light btn-lg mx-2">Browse Marketplace</a>
        </div>
    </div>
</div>

<!-- Rest of your content remains the same -->
<div class="container py-5">
    <div class="row text-center g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-tractor fa-3x text-success mb-3"></i>
                    <h5>For Farmers</h5>
                    <p>Increase income by 30-45% by selling directly to bulk buyers without middlemen</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-utensils fa-3x text-success mb-3"></i>
                    <h5>For Restaurants</h5>
                    <p>Save 15-25% on food procurement costs with direct farm sourcing</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-mobile-alt fa-3x text-success mb-3"></i>
                    <h5>Mobile-First</h5>
                    <p>Access via App, Web, or USSD *150*50# - no internet required</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                    <h5>Secure Escrow</h5>
                    <p>Payments held securely until you confirm delivery satisfaction</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">How SokoPata Works</h2>
        <div class="row text-center">
            <div class="col-md-2">
                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">1</div>
                <p>Browse Products</p>
            </div>
            <div class="col-md-2">
                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">2</div>
                <p>Place Order</p>
            </div>
            <div class="col-md-2">
                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">3</div>
                <p>Pay via Mobile Money</p>
            </div>
            <div class="col-md-2">
                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">4</div>
                <p>Supplier Prepares</p>
            </div>
            <div class="col-md-2">
                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">5</div>
                <p>Delivery & Confirm</p>
            </div>
            <div class="col-md-2">
                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">6</div>
                <p>Payment Released</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h2>Supported Payment Methods</h2>
            <p class="lead">Multiple payment options for all Tanzanians</p>
            <div class="row g-3 mt-3">
                <div class="col-4">
                    <div class="bg-light p-3 text-center rounded">
                        <strong>M-Pesa</strong>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-light p-3 text-center rounded">
                        <strong>Tigo Pesa</strong>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-light p-3 text-center rounded">
                        <strong>Airtel Money</strong>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-light p-3 text-center rounded">
                        <strong>HaloPesa</strong>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-light p-3 text-center rounded">
                        <strong>Pesapal</strong>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-light p-3 text-center rounded">
                        <strong>Bank Transfer</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body text-center py-4">
                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                    <h3>Join 6,000+ SMEs</h3>
                    <p>By Year 3, we'll have onboarded over 6,000 suppliers and 5,500 buyers across 10 regions of Tanzania</p>
                    <a href="{{ url('/register') }}" class="btn btn-light mt-2">Join SokoPata Today</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection