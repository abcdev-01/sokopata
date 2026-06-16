<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('supplier')->where('is_available', true);
        
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->category) {
            $query->where('category', $request->category);
        }
        
        $products = $query->latest()->paginate(20);
        $categories = ['vegetables', 'fruits', 'grains', 'dairy', 'meat', 'fish', 'processed'];
        
        return view('products.index', compact('products', 'categories'));
    }
    
    public function create()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        
        // Check if user is farmer or cooperative
        if (!in_array(Auth::user()->user_type, ['farmer', 'cooperative'])) {
            abort(403, 'Only farmers and cooperatives can list products.');
        }
        
        return view('products.create');
    }
    
    public function store(Request $request)
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'unit' => 'required',
            'quantity' => 'required|numeric|min:0',
            'location' => 'required',
        ]);
        
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $imageName);
        }
        
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
    
    public function show(Product $product)
    {
        $product->increment('views_count');
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
        
        return view('products.show', compact('product', 'relatedProducts'));
    }
    
    public function edit(Product $product)
    {
        if ($product->supplier_id !== Auth::id()) {
            abort(403);
        }
        return view('products.edit', compact('product'));
    }
    
    public function update(Request $request, Product $product)
    {
        if ($product->supplier_id !== Auth::id()) {
            abort(403);
        }
        
        $product->update($request->only(['quantity', 'price', 'is_available', 'description']));
        
        if ($request->hasFile('image')) {
            if ($product->image && Storage::exists('public/products/' . $product->image)) {
                Storage::delete('public/products/' . $product->image);
            }
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $imageName);
            $product->image = $imageName;
            $product->save();
        }
        
        return redirect()->back()->with('success', 'Product updated successfully!');
    }
}