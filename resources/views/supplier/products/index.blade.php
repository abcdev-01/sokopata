<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Products</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('supplier.products.create') }}">Add Product</a>

            @foreach($products as $product)
                <div class="border p-4 mb-3">
                    <h3>{{ $product->name }}</h3>
                    <p>{{ $product->price }} per {{ $product->unit }}</p>
                    <p>{{ $product->quantity }} available</p>
                    <p>{{ $product->location }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>