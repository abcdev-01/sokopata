<nav class="bg-white border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-green-700">
                SokoPata
            </a>

            <div class="flex items-center gap-4">
                <a href="{{ route('products.index') }}" class="text-sm font-medium hover:text-green-700">Products</a>
                <a href="{{ route('cart.index') }}" class="text-sm font-medium hover:text-green-700">Cart</a>

                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium hover:text-green-700">Dashboard</a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium hover:text-green-700">Login</a>
                    <a href="{{ route('register') }}" class="text-sm font-medium hover:text-green-700">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>