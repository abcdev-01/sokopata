<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
</head>
<body>
    @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('orders.store') }}">
        @csrf

        <div>
            <label for="buyer_id">Buyer ID</label>
            <input type="number" id="buyer_id" name="buyer_id" value="{{ old('buyer_id', 1) }}" required>
        </div>

        <div>
            <label for="order_number">Order Number</label>
            <input type="text" id="order_number" name="order_number" value="{{ old('order_number', 'SP-DEMO003') }}" required>
        </div>

        <div>
            <label for="delivery_address">Delivery Address</label>
            <input type="text" id="delivery_address" name="delivery_address" value="{{ old('delivery_address', 'Dar es Salaam, Tanzania') }}" required>
        </div>

        <div>
            <label for="delivery_phone">Delivery Phone</label>
            <input type="text" id="delivery_phone" name="delivery_phone" value="{{ old('delivery_phone', '+255123456789') }}" required>
        </div>

        <h3>Items</h3>

        <div style="margin-bottom: 15px;">
            <label>Product ID</label>
            <input type="number" name="items[0][product_id]" value="1" required>

            <label>Supplier ID</label>
            <input type="number" name="items[0][supplier_id]" value="1" required>

            <label>Product Name</label>
            <input type="text" name="items[0][product_name]" value="Fresh Tomatoes" required>

            <label>Price</label>
            <input type="number" step="0.01" name="items[0][price]" value="20.00" required>

            <label>Quantity</label>
            <input type="number" name="items[0][quantity]" value="5" required>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Product ID</label>
            <input type="number" name="items[1][product_id]" value="2" required>

            <label>Supplier ID</label>
            <input type="number" name="items[1][supplier_id]" value="1" required>

            <label>Product Name</label>
            <input type="text" name="items[1][product_name]" value="Onions" required>

            <label>Price</label>
            <input type="number" step="0.01" name="items[1][price]" value="15.00" required>

            <label>Quantity</label>
            <input type="number" name="items[1][quantity]" value="4" required>
        </div>

        <button type="submit">Save Order</button>
    </form>
</body>
</html>