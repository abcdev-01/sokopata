<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Check if user is admin
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if ($user && $user->user_type !== 'admin') {
                abort(403, 'Unauthorized access. Admin only.');
            }
            return $next($request);
        });
    }

    // Dashboard
    public function index()
    {
        $totalUsers = User::count();
        $totalFarmers = User::where('user_type', 'farmer')->count();
        $totalCooperatives = User::where('user_type', 'cooperative')->count();
        $totalBuyers = User::where('user_type', 'buyer')->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('commission');
        
        $recentOrders = Order::with(['buyer', 'supplier'])->latest()->limit(10)->get();
        $recentUsers = User::latest()->limit(10)->get();
        
        return view('admin.dashboard', compact(
            'totalUsers', 'totalFarmers', 'totalCooperatives', 'totalBuyers',
            'totalProducts', 'totalOrders', 'totalRevenue', 'recentOrders', 'recentUsers'
        ));
    }

    // Manage Users
    public function users()
    {
        $users = User::paginate(20);
        return view('admin.users', compact('users'));
    }

    public function editUser(int $id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|unique:users,phone,' . $id,
            'user_type' => 'required|in:farmer,cooperative,buyer,admin',
            'is_verified' => 'boolean'
        ]);
        
        $user->update($request->only(['name', 'email', 'phone', 'user_type', 'is_verified']));
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $user->password = Hash::make($request->password);
            $user->save();
        }
        
        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function deleteUser(int $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();
        
        // Prevent admin from deleting themselves
        if ($user->id === $currentUser->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    // Manage Products
    public function products()
    {
        $products = Product::with('supplier')->paginate(20);
        return view('admin.products', compact('products'));
    }

    public function editProduct(int $id)
    {
        $product = Product::with('supplier')->findOrFail($id);
        return view('admin.edit-product', compact('product'));
    }

    public function updateProduct(Request $request, int $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'is_available' => 'boolean'
        ]);
        
        $product->update($request->only(['name', 'category', 'price', 'quantity', 'is_available']));
        
        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct(int $id)
    {
        $product = Product::findOrFail($id);
        
        // Delete product image if exists
        if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
            Storage::disk('public')->delete('products/' . $product->image);
        }
        
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
    }

    // Manage Orders
    public function orders()
    {
        $orders = Order::with(['buyer', 'supplier', 'items.product'])->latest()->paginate(20);
        return view('admin.orders', compact('orders'));
    }

    public function viewOrder(int $id)
    {
        $order = Order::with(['buyer', 'supplier', 'items.product'])->findOrFail($id);
        return view('admin.view-order', compact('order'));
    }

    public function updateOrderStatus(Request $request, int $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,payment_pending,payment_confirmed,processing,dispatched,delivered,completed,cancelled'
        ]);
        
        $order->status = $request->status;
        
        if ($request->status === 'delivered') {
            $order->delivered_at = now();
        }
        
        if ($request->status === 'completed') {
            $order->payment_released = true;
            $order->payment_released_at = now();
        }
        
        $order->save();
        
        return redirect()->route('admin.orders')->with('success', 'Order status updated!');
    }

    public function deleteOrder(int $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders')->with('success', 'Order deleted successfully!');
    }
}