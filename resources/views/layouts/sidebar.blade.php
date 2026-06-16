<aside class="w-64 bg-slate-900 text-white min-h-screen hidden lg:block">
    <div class="p-6 border-b border-slate-700">
        <h1 class="text-2xl font-bold">SokoPata</h1>
        <p class="text-sm text-slate-400 mt-1">From farm to fork</p>
    </div>

    <nav class="p-4 space-y-2 text-sm">
        @auth
            @if(auth()->user()->role === 'buyer')
                <a href="{{ route('buyer.dashboard') }}" class="block px-4 py-3 rounded-lg hover:bg-slate-800">Buyer Dashboard</a>
            @endif

            @if(auth()->user()->role === 'supplier')
                <a href="{{ route('supplier.dashboard') }}" class="block px-4 py-3 rounded-lg hover:bg-slate-800">Supplier Dashboard</a>
            @endif

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg hover:bg-slate-800">Admin Dashboard</a>
            @endif

            <a href="{{ route('payments.index') }}" class="block px-4 py-3 rounded-lg hover:bg-slate-800">Payments & Escrow</a>
            <a href="{{ route('transactions.index') }}" class="block px-4 py-3 rounded-lg hover:bg-slate-800">Transactions</a>
        @endauth
    </nav>
</aside>