<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SokoPata - B2B Agricultural Marketplace</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .footer {
            margin-top: auto;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .role-badge {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        /* Heart icon animation */
        .heart-icon {
            transition: transform 0.3s;
        }
        .heart-icon:hover {
            transform: scale(1.1);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-leaf me-2"></i>SokoPata
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/products') }}">
                            <i class="fas fa-store me-1"></i>Marketplace
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/dashboard') }}">
                                <i class="fas fa-chart-line me-1"></i>Dashboard
                            </a>
                        </li>
                        
                        <!-- Role-Specific Menu Items -->
                        @if(auth()->user()->user_type == 'farmer' || auth()->user()->user_type == 'cooperative')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-store me-1"></i>Seller Menu
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ url('/seller/add-product') }}">
                                        <i class="fas fa-plus-circle me-2"></i>Add Product
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ url('/seller/products') }}">
                                        <i class="fas fa-boxes me-2"></i>My Products
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ url('/seller/dashboard') }}">
                                        <i class="fas fa-chart-simple me-2"></i>Seller Analytics
                                    </a></li>
                                </ul>
                            </li>
                        @endif
                        
                        @if(auth()->user()->user_type == 'buyer')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/orders') }}">
                                    <i class="fas fa-shopping-bag me-1"></i>My Orders
                                </a>
                            </li>
                        @endif
                        
                        @if(auth()->user()->user_type == 'admin')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-crown me-1"></i>Admin Menu
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ url('/admin/users') }}">
                                        <i class="fas fa-users me-2"></i>Manage Users
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ url('/admin/products') }}">
                                        <i class="fas fa-boxes me-2"></i>All Products
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ url('/admin/orders') }}">
                                        <i class="fas fa-truck me-2"></i>All Orders
                                    </a></li>
                                </ul>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <!-- Cart & Wishlist - Only visible to Buyers -->
                        @if(auth()->user()->user_type == 'buyer')
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ url('/cart') }}">
                                <i class="fas fa-shopping-cart"></i>
                                @php
                                    $cartCount = session()->has('cart') ? count(session('cart')) : 0;
                                @endphp
                                @if($cartCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link heart-icon" href="{{ url('/wishlist') }}">
                                <i class="fas fa-heart"></i>
                            </a>
                        </li>
                        @endif
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                                @php
                                    $roleColors = [
                                        'farmer' => 'badge bg-info',
                                        'cooperative' => 'badge bg-primary',
                                        'buyer' => 'badge bg-warning',
                                        'admin' => 'badge bg-danger'
                                    ];
                                @endphp
                                <span class="{{ $roleColors[auth()->user()->user_type] ?? 'badge bg-secondary' }} ms-1">
                                    {{ ucfirst(auth()->user()->user_type) }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ url('/profile') }}">
                                    <i class="fas fa-id-card me-2"></i>My Profile
                                </a></li>
                                
                                <!-- My Orders - Only visible to Buyers -->
                                @if(auth()->user()->user_type == 'buyer')
                                <li><a class="dropdown-item" href="{{ url('/orders') }}">
                                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                                </a></li>
                                @endif
                                
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ url('/logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-light text-success px-3" href="{{ url('/register') }}">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
                <div class="container">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
                <div class="container">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
                <div class="container">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>

    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-leaf me-2"></i>SokoPata</h5>
                    <p>From Farm to Fork, Seamlessly. Tanzania's first dedicated B2B agricultural marketplace.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="{{ url('/products') }}" class="text-white-50 text-decoration-none">Marketplace</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">How it Works</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact & Support</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone me-2"></i> *150*50#</li>
                        <li><i class="fas fa-envelope me-2"></i> info@sokopata.co.tz</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Dar es Salaam, Tanzania</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <small>&copy; {{ date('Y') }} SokoPata. Connecting Tanzania's Fields to Your Table.</small>
            </div>
        </div>
    </footer>

    <!-- Role Badge (Floating) -->
    @auth
    <div class="role-badge">
        <span class="badge bg-dark opacity-75">
            <i class="fas fa-user-tag me-1"></i>
            {{ ucfirst(auth()->user()->user_type) }}
        </span>
    </div>
    @endauth

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
     
    @auth
<script>
    // Auto logout after 2 hours of inactivity
    let timeout;
    const sessionTimeout = 120 * 60 * 1000; // 2 hours in milliseconds
    
    function resetTimer() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            // Show warning before logout
            if(confirm('Your session will expire due to inactivity. Click OK to stay logged in, or Cancel to logout now.')) {
                resetTimer();
                // Refresh session by making a request
                fetch('{{ url("/dashboard") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
            } else {
                // Logout
                document.getElementById('logout-form').submit();
            }
        }, sessionTimeout);
    }
    
    // Reset timer on user activity
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.onscroll = resetTimer;
    document.onclick = resetTimer;
</script>

<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endauth

</body>
</html>