<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:3'
        ]);
        
        // Check if user already reviewed this product
        $exists = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'You have already reviewed this product');
        }
        
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
        
        return back()->with('success', 'Review submitted successfully!');
    }
}