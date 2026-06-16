<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order Details</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Status:</strong> {{ $order->status }}</p>
            <p><strong>Payment:</strong> {{ $order->payment_status }}</p>
            <p><strong>Delivery:</strong> {{ $order->delivery_status }}</p>
            <p><strong>Total:</strong> {{ $order->total_amount }}</p>
        </div>
    </div>
</x-app-layout>