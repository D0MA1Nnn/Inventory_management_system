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

Route::get('/purchases-ui', [PurchaseController::class, 'index']);

// ========== API ROUTES ==========
// Category API
Route::get('/api/categories', [CategoryController::class, 'index']);
Route::get('/api/categories/{id}', [CategoryController::class, 'show']);
Route::post('/api/categories', [CategoryController::class, 'store']);
Route::put('/api/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/api/categories/{id}', [CategoryController::class, 'destroy']);

// Product API
Route::get('/api/products', [ProductPageController::class, 'index']);
Route::get('/api/products/{id}', [ProductPageController::class, 'show']);
Route::post('/api/products', [ProductPageController::class, 'store']);
Route::put('/api/products/{id}', [ProductPageController::class, 'update']);
Route::delete('/api/products/{id}', [ProductPageController::class, 'destroy']);

// Supplier API
Route::get('/api/suppliers', [SupplierController::class, 'index']);
Route::get('/api/suppliers/{id}', [SupplierController::class, 'show']);
Route::post('/api/suppliers', [SupplierController::class, 'store']);
Route::put('/api/suppliers/{id}', [SupplierController::class, 'update']);
Route::delete('/api/suppliers/{id}', [SupplierController::class, 'destroy']);

// ========== PURCHASE API ROUTES ==========
Route::get('/api/purchase/products', [PurchaseController::class, 'getProducts']);
Route::get('/api/purchase/product-suppliers/{productId}', [PurchaseController::class, 'getProductSuppliers']);
Route::post('/api/purchase/add-to-coming', [PurchaseController::class, 'addToComing']);
Route::get('/api/purchase/coming', [PurchaseController::class, 'getComing']);
Route::post('/api/purchase/receive', [PurchaseController::class, 'receive']);
Route::get('/api/purchase/received', [PurchaseController::class, 'getReceived']);
