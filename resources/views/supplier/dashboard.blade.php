@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow border border-emerald-100 p-6">
        <h1 class="text-2xl font-bold text-emerald-700">Supplier Dashboard</h1>
        <p class="mt-2 text-gray-600">Welcome back, {{ auth()->user()->name }}.</p>
    </div>

    <div class="bg-white rounded-2xl shadow border border-emerald-100 p-6">
        <div class="flex flex-wrap gap-3">
            @if (Route::has('payments.index'))
                <a href="{{ route('payments.index') }}" class="px-4 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700">
                    View Payments
                </a>
            @endif

            @if (Route::has('orders.create'))
                <a href="{{ route('orders.create') }}" class="px-4 py-2 rounded bg-white text-emerald-700 border border-emerald-300 hover:bg-emerald-50">
                    Create Order
                </a>
            @endif
        </div>
    </div>
</div>
@endsection