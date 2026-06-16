@extends('layouts.app')

@section('content')
@php
    $cartItems = $cartItems ?? [];
@endphp

<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Shopping Cart</h1>
        <p class="text-gray-500">Review your selected items.</p>
    </div>

    @if(count($cartItems) > 0)
        <div class="bg-white rounded-xl shadow border overflow-hidden">
            <table class="min-w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4">Product</th>
                        <th class="p-4">Price</th>
                        <th class="p-4">Qty</th>
                        <th class="p-4">Subtotal</th>
                        <th class="p-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr class="border-t">
                            <td class="p-4">{{ $item['name'] ?? '' }}</td>
                            <td class="p-4">Ksh {{ $item['price'] ?? 0 }}</td>
                            <td class="p-4">{{ $item['quantity'] ?? 0 }}</td>
                            <td class="p-4">Ksh {{ $item['subtotal'] ?? 0 }}</td>
                            <td class="p-4">
                                <form method="POST" action="{{ route('cart.remove', $item['product_id'] ?? 0) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl shadow border p-6 flex justify-between items-center">
            <span class="font-semibold">Total</span>
            <span class="text-xl font-bold">Ksh {{ $total ?? 0 }}</span>
        </div>
    @else
        <div class="bg-white rounded-xl shadow border p-6">
            Your cart is empty.
        </div>
    @endif
</div>
@endsection