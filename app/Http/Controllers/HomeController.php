<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (Auth::check()) {
            // Logout the user
            Auth::logout();
            
            // Clear all session data
            session()->flush();
            
            // Regenerate session ID
            session()->regenerate();
            
            // Redirect to home with message
            return redirect('/')->with('message', 'You have been logged out. Please login to access your account.');
        }
        
        return view('landing');
    }
}