<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Home route with auto-logout
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public routes
Route::get('/about', function () { return view('about'); })->name('about');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    // Wishlist Routes
Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add/{productId}', [App\Http\Controllers\WishlistController::class, 'add'])->name('wishlist.add');
Route::delete('/wishlist/remove/{id}', [App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');

// Review Routes
Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Protected routes (require login)
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Seller routes (will check role inside controller)
    Route::prefix('seller')->name('seller.')->group(function () {
        Route::get('/add-product', [SellerController::class, 'addProduct'])->name('add-product');
        Route::post('/store-product', [SellerController::class, 'storeProduct'])->name('store-product');
        Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
        Route::get('/products', [SellerController::class, 'myProducts'])->name('products');
        Route::get('/product/{id}/edit', [SellerController::class, 'editProduct'])->name('edit-product');
        Route::put('/product/{id}', [SellerController::class, 'updateProduct'])->name('update-product');
        Route::delete('/product/{id}', [SellerController::class, 'deleteProduct'])->name('delete-product');
    });
    
    // Simple routes
    Route::get('/add-product', [SellerController::class, 'addProduct'])->name('simple.add-product');
    Route::post('/store-product', [SellerController::class, 'storeProduct'])->name('simple.store-product');
    
    // Cart routes
    Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{product}', [OrderController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update/{product}', [OrderController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{product}', [OrderController::class, 'removeFromCart'])->name('cart.remove');
    
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'placeOrder'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'myOrders'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    
    Route::get('/payment/{order}', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('/payment/mpesa/{order}', [PaymentController::class, 'simulateMpesaPayment'])->name('payment.mpesa');
    Route::post('/payment/confirm/{order}', [PaymentController::class, 'confirmDelivery'])->name('payment.confirm');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');
    
    Route::get('/products', [App\Http\Controllers\AdminController::class, 'products'])->name('products');
    Route::get('/products/{id}/edit', [App\Http\Controllers\AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [App\Http\Controllers\AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [App\Http\Controllers\AdminController::class, 'deleteProduct'])->name('products.delete');
    
    Route::get('/orders', [App\Http\Controllers\AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [App\Http\Controllers\AdminController::class, 'viewOrder'])->name('orders.view');
    Route::put('/orders/{id}/status', [App\Http\Controllers\AdminController::class, 'updateOrderStatus'])->name('orders.status');
    Route::delete('/orders/{id}', [App\Http\Controllers\AdminController::class, 'deleteOrder'])->name('orders.delete');
});

// M-Pesa Routes
Route::prefix('mpesa')->name('mpesa.')->group(function () {
    Route::post('/initiate/{order}', [App\Http\Controllers\MpesaController::class, 'initiatePayment'])->name('initiate');
    Route::post('/callback', [App\Http\Controllers\MpesaController::class, 'callback'])->name('callback');
    Route::get('/status/{order}', [App\Http\Controllers\MpesaController::class, 'checkStatus'])->name('check');
});
// Pesapal Payment Routes
Route::prefix('pesapal')->name('pesapal.')->group(function () {
    Route::get('/pay/{order}', [App\Http\Controllers\PesapalController::class, 'showPaymentForm'])->name('pay');
    Route::post('/initiate/{order}', [App\Http\Controllers\PesapalController::class, 'initiatePayment'])->name('initiate');
    Route::get('/callback', [App\Http\Controllers\PesapalController::class, 'callback'])->name('callback');
    Route::get('/simulate-success/{order}', [App\Http\Controllers\PesapalController::class, 'simulateSuccess'])->name('simulate.success');
    Route::post('/ipn', [App\Http\Controllers\PesapalController::class, 'ipn'])->name('ipn');
    Route::get('/pesapal/simulate-success/{order}', [App\Http\Controllers\PesapalController::class, 'simulateSuccess'])->name('pesapal.simulate.success');
    Route::get('/status/{order}', [App\Http\Controllers\PesapalController::class, 'checkStatus'])->name('status');
});