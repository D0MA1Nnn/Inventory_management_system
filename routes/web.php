<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// ========== PUBLIC LANDING PAGE ==========
Route::get('/', function () {
    return view('home');
})->name('home');

// ========== SHOP PAGE (For customers) ==========
Route::get('/shop', function () {
    return view('shop');
})->name('shop')->middleware('auth');

// ========== CART PAGE ==========
Route::get('/cart', function () {
    return view('cart');
})->name('cart')->middleware('auth');

// ========== AUTH ROUTES ==========
Route::middleware('web')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// ========== ADMIN DASHBOARD (Only for admin role) ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('admin');
    
    Route::get('/products-ui', function () {
        return view('products');
    })->name('products-ui')->middleware('admin');
    
    Route::get('/categories-ui', function () {
        return view('categories');
    })->name('categories-ui')->middleware('admin');
    
    Route::get('/suppliers-ui', function () {
        return view('suppliers');
    })->name('suppliers-ui')->middleware('admin');
    
    Route::get('/purchases-ui', [PurchaseController::class, 'index'])->name('purchases-ui')->middleware('admin');
    
    Route::get('/sales-ui', [SaleController::class, 'index'])->name('sales-ui')->middleware('admin');
});

// ========== API ROUTES ==========
Route::prefix('api')->group(function () {
    // Category API
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('auth');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->middleware('auth');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->middleware('auth');
    
    // Product API
    Route::get('/products', [ProductPageController::class, 'index']);
    Route::get('/products/{id}', [ProductPageController::class, 'show']);
    Route::post('/products', [ProductPageController::class, 'store'])->middleware('auth');
    Route::put('/products/{id}', [ProductPageController::class, 'update'])->middleware('auth');
    Route::delete('/products/{id}', [ProductPageController::class, 'destroy'])->middleware('auth');
    
    // Supplier API
    Route::get('/suppliers', [SupplierController::class, 'index']);
    Route::get('/suppliers/{id}', [SupplierController::class, 'show']);
    Route::post('/suppliers', [SupplierController::class, 'store'])->middleware('auth');
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->middleware('auth');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->middleware('auth');
    
    // Purchase API
    Route::get('/purchase/products', [PurchaseController::class, 'getProducts']);
    Route::get('/purchase/product-suppliers/{productId}', [PurchaseController::class, 'getProductSuppliers']);
    Route::post('/purchase/add-to-coming', [PurchaseController::class, 'addToComing'])->middleware('auth');
    Route::get('/purchase/coming', [PurchaseController::class, 'getComing']);
    Route::post('/purchase/receive', [PurchaseController::class, 'receive'])->middleware('auth');
    Route::get('/purchase/received', [PurchaseController::class, 'getReceived']);
    
    // Sales API - Make sure these are outside any middleware that might block them
    Route::get('/sales', [SaleController::class, 'getSales']);
    Route::get('/sales/stats', [SaleController::class, 'getSalesStats']);
    Route::get('/sales/category', [SaleController::class, 'getSalesByCategory']);
    Route::get('/sales/recent', [SaleController::class, 'getRecentSales']);
    Route::post('/sales', [SaleController::class, 'store']);
    Route::delete('/sales/{id}', [SaleController::class, 'destroy']);
});