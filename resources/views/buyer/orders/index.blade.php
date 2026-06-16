<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Orders</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach($orders as $order)
                <div class="border p-4 mb-3">
                    <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                    <p><strong>Status:</strong> {{ $order->status }}</p>
                    <p><strong>Payment:</strong> {{ $order->payment_status }}</p>
                    <a href="{{ route('buyer.orders.show', $order) }}">View Details</a>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>