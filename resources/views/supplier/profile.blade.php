<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Supplier Profile</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p><strong>Business Name:</strong> {{ $supplier->business_name }}</p>
            <p><strong>Phone:</strong> {{ $supplier->phone }}</p>
            <p><strong>Location:</strong> {{ $supplier->location }}</p>
            <p><strong>Status:</strong> {{ $supplier->status }}</p>

            <a href="{{ route('supplier.profile.edit') }}">Edit Profile</a>
        </div>
    </div>
</x-app-layout>