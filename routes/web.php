<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// ========== PUBLIC LANDING PAGE ==========
Route::get('/', function () {
    return view('home');
})->name('home');

// ========== SHOP PAGE ==========
Route::get('/shop', function () {
    return view('shop');
})->name('shop')->middleware('auth');

// ========== CART ==========
Route::get('/cart', function () {
    return view('cart');
})->name('cart')->middleware('auth');

// ========== AUTH ==========
Route::middleware('web')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// ========== MAIN SYSTEM ==========
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::get('/products-ui', fn() => view('products'))->name('products-ui');
    Route::get('/categories-ui', fn() => view('categories'))
        ->middleware('role:admin,manager')
        ->name('categories-ui');
    Route::get('/suppliers-ui', fn() => view('suppliers'))
        ->middleware('role:admin,manager')
        ->name('suppliers-ui');

    Route::get('/purchases-ui', [PurchaseController::class, 'index'])
        ->middleware('role:admin,manager')
        ->name('purchases-ui');
    Route::get('/sales-ui', [SaleController::class, 'index'])->name('sales-ui');

    Route::get('/staff-ui', [StaffController::class, 'index'])->name('staff-ui');

    // CUSTOMER DASHBOARD (ADMIN ONLY)
    Route::get('/customers-ui', [CustomerController::class, 'index'])
        ->name('customers-ui')
        ->middleware('admin');

    Route::get('/admin/logs', [ActivityLogController::class, 'index'])
        ->name('admin.logs.index')
        ->middleware('admin');
});


// ========== API ==========
Route::prefix('api')->group(function () {

    // CATEGORY
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store'])->middleware(['auth', 'role:admin,manager']);
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->middleware(['auth', 'role:admin,manager']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->middleware(['auth', 'role:admin,manager']);

    // PRODUCT
    Route::get('/products', [ProductPageController::class, 'index']);
    Route::get('/products/{id}', [ProductPageController::class, 'show']);
    Route::post('/products', [ProductPageController::class, 'store'])->middleware('auth');
    Route::put('/products/{id}', [ProductPageController::class, 'update'])->middleware('auth');
    Route::delete('/products/{id}', [ProductPageController::class, 'destroy'])->middleware('auth');

    // SUPPLIER
    Route::get('/suppliers', [SupplierController::class, 'index']);
    Route::get('/suppliers/{id}', [SupplierController::class, 'show']);
    Route::post('/suppliers', [SupplierController::class, 'store'])->middleware(['auth', 'role:admin,manager']);
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->middleware(['auth', 'role:admin,manager']);
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->middleware(['auth', 'role:admin,manager']);

    // PURCHASE
    Route::get('/purchase/products', [PurchaseController::class, 'getProducts']);
    Route::get('/purchase/product-suppliers/{productId}', [PurchaseController::class, 'getProductSuppliers']);
    Route::post('/purchase/add-to-coming', [PurchaseController::class, 'addToComing'])->middleware(['auth', 'role:admin,manager']);
    Route::post('/purchase/receive', [PurchaseController::class, 'receive'])->middleware(['auth', 'role:admin,manager']);
    Route::get('/purchase/received', [PurchaseController::class, 'getReceived'])->middleware(['auth', 'role:admin,manager']);
    Route::get('/purchase/coming', [PurchaseController::class, 'getComing'])->middleware(['auth', 'role:admin,manager']);

    // SALES
    Route::get('/sales', [SaleController::class, 'getSales']);
    Route::get('/sales/stats', [SaleController::class, 'getSalesStats']);
    Route::get('/sales/category', [SaleController::class, 'getSalesByCategory']);
    Route::get('/sales/recent', [SaleController::class, 'getRecentSales']);
    Route::post('/sales', [SaleController::class, 'store']);
    Route::delete('/sales/{id}', [SaleController::class, 'destroy']);
    Route::get('/sales/analytics', [SaleController::class, 'analytics']);

    // STAFF
    Route::get('/staff', [StaffController::class, 'getStaff']);
    Route::get('/staff/stats', [StaffController::class, 'getStats']);
    Route::get('/staff/{id}', [StaffController::class, 'show']);
    Route::post('/staff', [StaffController::class, 'store'])->middleware('auth');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->middleware('auth');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->middleware('auth');

    // CUSTOMER APIs
    Route::get('/customers', [CustomerController::class, 'getCustomers'])
        ->middleware(['auth','admin']);

    Route::get('/customers/stats', [CustomerController::class, 'getStats'])
        ->middleware(['auth','admin']);

    // PASSWORD CHECK
    Route::post('/verify-password', function(Request $request) {
        $user = Auth::user();
        return response()->json([
            'valid' => Hash::check($request->password, $user->password)
        ]);
    })->middleware('auth');
});