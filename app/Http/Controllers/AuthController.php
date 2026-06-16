<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'user_type' => 'required|in:farmer,buyer,cooperative',
            'business_name' => 'required_if:user_type,farmer,cooperative',
            'national_id' => 'required_if:user_type,farmer,cooperative'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'business_name' => $request->business_name,
            'national_id' => $request->national_id,
            'address' => $request->address,
            'city' => $request->city ?? 'Dar es Salaam'
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful! Please complete your KYC verification.');
    }
     
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate(); // Regenerate session ID
        $request->session()->regenerateToken(); // Regenerate CSRF token
        
        // Set session timeout (optional)
        session(['last_activity' => time()]);
        
        return redirect()->intended(route('dashboard'));
    }

    return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}