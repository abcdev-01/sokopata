<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buyer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Recent Orders</h3>

                @if ($orders->isEmpty())
                    <p>No orders found.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($orders as $order)
                            <div class="border rounded p-4">
                                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                                <p><strong>Status:</strong> {{ $order->status ?? 'Pending' }}</p>
                                <p><strong>Created:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>