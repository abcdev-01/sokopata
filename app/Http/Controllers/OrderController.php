<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function cart()
    {
        $cart = session()->get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get();
        $total = 0;
        
        foreach ($products as $product) {
            $total += $product->price * $cart[$product->id];
        }
        
        return view('orders.cart', compact('products', 'cart', 'total'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        
        $quantity = $request->quantity ?? 1;
        
        if ($quantity < $product->min_order_quantity) {
            return back()->with('error', "Minimum order quantity is {$product->min_order_quantity}");
        }
        
        if ($quantity > $product->quantity) {
            return back()->with('error', "Only {$product->quantity} available");
        }
        
        $cart[$product->id] = $quantity;
        session()->put('cart', $cart);
        
        return back()->with('success', 'Product added to cart!');
    }

    public function removeFromCart(Product $product)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart')->with('success', 'Product removed from cart');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty');
        }
        
        $products = Product::whereIn('id', array_keys($cart))->get();
        $subtotal = 0;
        
        foreach ($products as $product) {
            $subtotal += $product->price * $cart[$product->id];
        }
        
        $commission = $subtotal * 0.07; // 7% platform commission
        $deliveryFee = 5000; // Base delivery fee
        $total = $subtotal + $commission + $deliveryFee;
        
        return view('orders.checkout', compact('products', 'cart', 'subtotal', 'commission', 'deliveryFee', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string',
            'payment_method' => 'required|in:mpesa,tigo_pesa,airtel_money,halopesa,pesapal,bank_transfer'
        ]);
        
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Cart is empty');
        }
        
        $products = Product::whereIn('id', array_keys($cart))->get();
        $subtotal = 0;
        $suppliers = [];
        
        foreach ($products as $product) {
            $subtotal += $product->price * $cart[$product->id];
            $suppliers[$product->supplier_id] = true;
        }
        
        if (count($suppliers) > 1) {
            return back()->with('error', 'Please order from one supplier at a time');
        }
        
        $supplierId = array_key_first($suppliers);
        $commission = $subtotal * 0.07;
        $deliveryFee = 5000;
        $total = $subtotal + $commission + $deliveryFee;
        
        $order = Order::create([
            'order_number' => 'SP-' . strtoupper(Str::random(10)),
            'buyer_id' => Auth::id(),
            'supplier_id' => $supplierId,
            'subtotal' => $subtotal,
            'commission' => $commission,
            'delivery_fee' => $deliveryFee,
            'total_amount' => $total,
            'status' => 'payment_pending',
            'payment_method' => $request->payment_method,
            'delivery_address' => $request->delivery_address
        ]);
        
        foreach ($products as $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $cart[$product->id],
                'unit_price' => $product->price,
                'total_price' => $product->price * $cart[$product->id]
            ]);
            
            // Update product quantity
            $product->decrement('quantity', $cart[$product->id]);
        }
        
        session()->forget('cart');
        
        // Redirect to payment
         return redirect()->route('pesapal.pay', $order);
    }

    public function myOrders()
    {
        $userId = Auth::id();
        
        $orders = Order::where('buyer_id', $userId)
            ->orWhere('supplier_id', $userId)
            ->with(['buyer', 'supplier', 'items.product'])
            ->latest()
            ->paginate(10);
        
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Check if user is authorized to view this order
        if ($order->buyer_id !== Auth::id() && $order->supplier_id !== Auth::id()) {
            abort(403);
        }
        
        $order->load(['buyer', 'supplier', 'items.product']);
        
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
{
    // Check if user is the supplier
    if ($order->supplier_id !== Auth::id()) {
        abort(403);
    }
    
    $request->validate([
        'status' => 'required|in:processing,dispatched,delivered,cancelled'
    ]);
    
    if ($request->status === 'delivered') {
        $order->delivered_at = \Carbon\Carbon::now();
        $order->save();
    }
    
    $order->update(['status' => $request->status]);
    
    return back()->with('success', 'Order status updated!');
}
    public function updateCart(Request $request, Product $product)
{
    $cart = session()->get('cart', []);
    
    $quantity = $request->quantity ?? 1;
    
    if ($quantity <= 0) {
        unset($cart[$product->id]);
    } else {
        if ($quantity > $product->quantity) {
            return back()->with('error', "Only {$product->quantity} available");
        }
        $cart[$product->id] = $quantity;
    }
    
    session()->put('cart', $cart);
    
    return redirect()->route('cart')->with('success', 'Cart updated successfully!');
}
}