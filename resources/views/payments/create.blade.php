<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pay Order {{ $order->order_number }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p>Total Amount: {{ $order->total_amount }}</p>

            <form action="{{ route('payments.store', $order) }}" method="POST">
                @csrf

                <label>Payment Method</label>
                <select name="method">
                    <option value="M-Pesa">M-Pesa</option>
                    <option value="Tigo Pesa">Tigo Pesa</option>
                    <option value="Airtel Money">Airtel Money</option>
                    <option value="HaloPesa">HaloPesa</option>
                    <option value="Pesapal">Pesapal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>

                <label>Transaction Reference</label>
                <input type="text" name="transaction_ref">

                <button type="submit">Confirm Payment</button>
            </form>
        </div>
    </div>
</x-app-layout>