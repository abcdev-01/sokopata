<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Supplier Approval</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach($suppliers as $supplier)
                <div class="border p-4 mb-3">
                    <p><strong>Business:</strong> {{ $supplier->business_name }}</p>
                    <p><strong>Phone:</strong> {{ $supplier->phone }}</p>
                    <p><strong>Status:</strong> {{ $supplier->status }}</p>

                    <form action="{{ route('admin.suppliers.approve', $supplier) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">Approve</button>
                    </form>

                    <form action="{{ route('admin.suppliers.reject', $supplier) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">Reject</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>