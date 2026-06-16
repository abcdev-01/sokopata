<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect to role-specific dashboard
        switch($user->user_type) {
            case 'admin':
                return $this->adminDashboard();
            case 'farmer':
            case 'cooperative':
                return $this->supplierDashboard();
            case 'buyer':
                return $this->buyerDashboard();
            default:
                return redirect('/')->with('error', 'Invalid user type');
        }
    }
    
    private function supplierDashboard()
    {
        $userId = Auth::id();
        
        $products = Product::where('supplier_id', $userId)->count();
        $activeProducts = Product::where('supplier_id', $userId)->where('is_available', true)->count();
        
        $orders = Order::where('supplier_id', $userId);
        $totalSales = $orders->sum('total_amount');
        $pendingOrders = $orders->where('status', 'payment_confirmed')->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        
        $recentOrders = Order::where('supplier_id', $userId)
            ->with('buyer')
            ->latest()
            ->limit(5)
            ->get();
        
        $recentProducts = Product::where('supplier_id', $userId)
            ->latest()
            ->limit(5)
            ->get();
        
        return view('dashboard.supplier', compact(
            'products', 'activeProducts', 'totalSales', 
            'pendingOrders', 'completedOrders', 'recentOrders', 'recentProducts'
        ));
    }
    
    private function buyerDashboard()
    {
        $userId = Auth::id();
        
        $orders = Order::where('buyer_id', $userId);
        $totalSpent = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        $pendingDelivery = $orders->where('status', 'dispatched')->count();
        $processingOrders = $orders->where('status', 'processing')->count();
        
        $recentOrders = Order::where('buyer_id', $userId)
            ->with('supplier', 'items.product')
            ->latest()
            ->limit(5)
            ->get();
        
        $recommendedProducts = Product::where('is_available', true)
            ->where('quantity', '>', 0)
            ->inRandomOrder()
            ->limit(6)
            ->get();
        
        return view('dashboard.buyer', compact(
            'totalSpent', 'totalOrders', 'completedOrders', 
            'pendingDelivery', 'processingOrders', 'recentOrders', 'recommendedProducts'
        ));
    }
    
    private function adminDashboard()
    {
        $totalUsers = User::count();
        $totalSuppliers = User::whereIn('user_type', ['farmer', 'cooperative'])->count();
        $totalBuyers = User::where('user_type', 'buyer')->count();
        $verifiedUsers = User::where('is_verified', true)->count();
        $newUsersToday = User::whereDate('created_at', today())->count();
        
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_available', true)->count();
        $outOfStock = Product::where('quantity', '<=', 0)->count();
        
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::whereIn('status', ['payment_confirmed', 'processing', 'dispatched'])->count();
        
        $totalRevenue = Order::where('status', 'completed')->sum('commission');
        
        // Recent activities
        $recentUsers = User::latest()->limit(5)->get();
        $recentOrders = Order::with(['buyer', 'supplier'])->latest()->limit(5)->get();
        $recentProducts = Product::with('supplier')->latest()->limit(5)->get();
        
        // Monthly revenue chart data
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(commission) as revenue'))
            ->groupBy('month')
            ->get();
        
        $topSuppliers = User::whereIn('user_type', ['farmer', 'cooperative'])
            ->withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(5)
            ->get();
        
        $topBuyers = User::where('user_type', 'buyer')
            ->withCount('ordersAsBuyer')
            ->orderBy('orders_as_buyer_count', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard.admin', compact(
            'totalUsers', 'totalSuppliers', 'totalBuyers', 'verifiedUsers', 'newUsersToday',
            'totalProducts', 'activeProducts', 'outOfStock',
            'totalOrders', 'completedOrders', 'pendingOrders',
            'totalRevenue', 'monthlyRevenue', 'topSuppliers', 'topBuyers',
            'recentUsers', 'recentOrders', 'recentProducts'
        ));
    }
}