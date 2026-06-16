<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->get();
        return view('wishlist.index', compact('wishlists'));
    }
    
    public function add(int $productId)
    {
        $product = Product::findOrFail($productId);
        
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();
            
        if (!$exists) {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            return back()->with('success', 'Product added to wishlist!');
        }
        
        return back()->with('info', 'Product already in wishlist');
    }
    
    public function remove(int $id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        $wishlist->delete();
        
        return redirect()->route('wishlist.index')->with('success', 'Product removed from wishlist');
    }
}