<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SellerController extends Controller
{
    /**
     * Show the form to add a new product
     */
    public function addProduct(): View|RedirectResponse
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        
        $user = Auth::user();
        
        // Check if user is a farmer or cooperative
        if (!in_array($user->user_type, ['farmer', 'cooperative'])) {
            abort(403, 'Only farmers and cooperatives can add products. Your account type: ' . $user->user_type);
        }
        
        return view('products.create');
    }
    
    /**
     * Store a new product
     */
    public function storeProduct(Request $request): RedirectResponse
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'location' => 'required|string',
            'min_order_quantity' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Handle image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $imageName);
        }
        
        // Create product
        Product::create([
            'supplier_id' => Auth::id(),
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'unit' => $request->unit,
            'quantity' => $request->quantity,
            'location' => $request->location,
            'min_order_quantity' => $request->min_order_quantity ?? 1,
            'image' => $imageName,
            'is_available' => true
        ]);
        
        return redirect()->route('products.index')->with('success', 'Product listed successfully!');
    }
    
    /**
     * Show seller dashboard
     */
    public function dashboard(): View|RedirectResponse
    {
        $user = Auth::user();
        
        if (!in_array($user->user_type, ['farmer', 'cooperative'])) {
            abort(403);
        }
        
        $products = Product::where('supplier_id', $user->id)->count();
        $activeProducts = Product::where('supplier_id', $user->id)->where('is_available', true)->count();
        $totalSales = Product::where('supplier_id', $user->id)->sum('price');
        
        return view('seller.dashboard', compact('products', 'activeProducts', 'totalSales'));
    }
    
    /**
     * Edit product
     */
    public function editProduct(int $id): View|RedirectResponse
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);
        
        // Check if product belongs to this seller
        if ($product->supplier_id != $user->id) {
            abort(403, 'Unauthorized access to this product.');
        }
        
        return view('products.edit', compact('product'));
    }
    
    /**
     * Update product
     */
    public function updateProduct(Request $request, int $id): RedirectResponse
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);
        
        // Check if product belongs to this seller
        if ($product->supplier_id != $user->id) {
            abort(403, 'Unauthorized access to this product.');
        }
        
        $request->validate([
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|numeric|min:0',
            'is_available' => 'nullable|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && Storage::exists('public/products/' . $product->image)) {
                Storage::delete('public/products/' . $product->image);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $imageName);
            $product->image = $imageName;
        }
        
        // Update product
        $product->update($request->only(['price', 'quantity', 'is_available', 'description']));
        $product->save();
        
        return redirect()->back()->with('success', 'Product updated successfully!');
    }
    
    /**
     * Delete product
     */
    public function deleteProduct(int $id): RedirectResponse
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);
        
        // Check if product belongs to this seller
        if ($product->supplier_id != $user->id) {
            abort(403, 'Unauthorized access to this product.');
        }
        
        // Delete product image
        if ($product->image && Storage::exists('public/products/' . $product->image)) {
            Storage::delete('public/products/' . $product->image);
        }
        
        $product->delete();
        
        return redirect()->route('seller.dashboard')->with('success', 'Product deleted successfully!');
    }
    
    /**
     * My Products List
     */
    public function myProducts(): View|RedirectResponse
    {
        $user = Auth::user();
        
        if (!in_array($user->user_type, ['farmer', 'cooperative'])) {
            abort(403);
        }
        
        $products = Product::where('supplier_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('seller.products', compact('products'));
    }
}