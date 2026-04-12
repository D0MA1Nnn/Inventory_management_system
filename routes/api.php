<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;

// ========== UI VIEWS ==========
Route::get('/', function () {
    return view('welcome');
});

Route::get('/products-ui', function () {
    return view('products');
});

Route::get('/categories-ui', function () {
    return view('categories');
});

Route::get('/suppliers-ui', function () {
    return view('suppliers');
});

// ========== API ROUTES (Temporary fix - move all API routes here) ==========
Route::prefix('api')->group(function () {
    // Category Routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    
    // Product Routes
    Route::get('/products', [ProductPageController::class, 'index']);
    Route::get('/products/{id}', [ProductPageController::class, 'show']);
    Route::post('/products', [ProductPageController::class, 'store']);
    Route::put('/products/{id}', [ProductPageController::class, 'update']);
    Route::delete('/products/{id}', [ProductPageController::class, 'destroy']);
    
    // Supplier Routes
    Route::get('/suppliers', [SupplierController::class, 'index']);
    Route::get('/suppliers/{id}', [SupplierController::class, 'show']);
    Route::post('/suppliers', [SupplierController::class, 'store']);
    Route::put('/suppliers/{id}', [SupplierController::class, 'update']);
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy']);

    // ========== PURCHASE ROUTES ==========
    Route::get('/purchase/products', [PurchaseController::class, 'getProducts']);
    Route::get('/purchase/product-suppliers/{productId}', [PurchaseController::class, 'getProductSuppliers']);
    Route::post('/purchase/add-to-coming', [PurchaseController::class, 'addToComing']);
    Route::get('/purchase/coming', [PurchaseController::class, 'getComing']);
    Route::post('/purchase/receive', [PurchaseController::class, 'receive']);
    Route::get('/purchase/received', [PurchaseController::class, 'getReceived']);
});