<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Supplier Profile</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('supplier.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div>
                    <label>Business Name</label>
                    <input type="text" name="business_name" value="{{ old('business_name', $supplier->business_name) }}">
                </div>

                <div>
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}">
                </div>

                <div>
                    <label>Location</label>
                    <input type="text" name="location" value="{{ old('location', $supplier->location) }}">
                </div>

                <button type="submit">Save</button>
            </form>
        </div>
    </div>
</x-app-layout>