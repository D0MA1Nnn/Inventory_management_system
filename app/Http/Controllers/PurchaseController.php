<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('purchases');
    }

    public function getProducts()
    {
        return Product::with('category')->get();
    }

    public function getProductSuppliers($productId)
    {
        $product = Product::with('suppliers')->find($productId);
        
        if (!$product) {
            return response()->json([]);
        }
        
        return response()->json($product->suppliers);
    }

    // Add to coming products - FIXED to use cost_price
    public function addToComing(Request $request)
    {
        $product = Product::find($request->product_id);
        $supplier = Supplier::find($request->supplier_id);
        
        if (!$product || !$supplier) {
            return response()->json(['error' => 'Product or supplier not found'], 404);
        }
        
        // USE COST PRICE for purchase (not selling price)
        $purchasePrice = $product->cost_price ?? $product->price;
        $total = $purchasePrice * $request->quantity;
        
        $purchase = Purchase::create([
            'product_id' => $product->id,
            'supplier_id' => $supplier->id,
            'quantity' => $request->quantity,
            'price' => $purchasePrice,  // Store cost price
            'total' => $total,
            'status' => 'pending'
        ]);
        
        return response()->json(['success' => true, 'purchase' => $purchase]);
    }

    // Get coming products (pending status) - formatted for frontend
    public function getComing()
    {
        $purchases = Purchase::with(['product', 'product.category', 'supplier'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $formatted = [];
        foreach ($purchases as $purchase) {
            $formatted[$purchase->id] = [
                'product_id' => $purchase->product_id,
                'product_name' => $purchase->product->name ?? 'N/A',
                'supplier_id' => $purchase->supplier_id,
                'supplier_name' => $purchase->supplier->name ?? 'N/A',
                'price' => $purchase->price,  // This is already cost price
                'quantity' => $purchase->quantity,
                'category' => $purchase->product->category->name ?? 'N/A',
                'product_image' => $purchase->product->image ?? null,
                'total' => $purchase->total,
                'added_at' => $purchase->created_at
            ];
        }
        
        return response()->json($formatted);
    }

    // Receive product (move from pending to received)
    public function receive(Request $request)
    {
        $purchase = Purchase::find($request->cart_key);
        
        if (!$purchase) {
            return response()->json(['error' => 'Purchase not found'], 404);
        }

        if ($purchase->status !== 'pending') {
            return response()->json(['error' => 'Purchase already received'], 422);
        }
        
        // Update product stock
        $product = Product::find($purchase->product_id);
        if ($product) {
            $product->quantity += $purchase->quantity;
            $product->save();
        }
        
        // Update purchase status
        $purchase->status = 'received';
        $purchase->received_at = now();
        $purchase->save();
        
        return response()->json(['success' => true]);
    }

    // Get received products - formatted for frontend
    public function getReceived()
    {
        $purchases = Purchase::with(['product', 'product.category', 'supplier'])
            ->where('status', 'received')
            ->orderBy('received_at', 'desc')
            ->get();
        
        $formatted = [];
        foreach ($purchases as $purchase) {
            $formatted[$purchase->id] = [
                'product_id' => $purchase->product_id,
                'product_name' => $purchase->product->name,
                'supplier_id' => $purchase->supplier_id,
                'supplier_name' => $purchase->supplier->name,
                'price' => $purchase->price,  // This is cost price
                'quantity' => $purchase->quantity,
                'category' => $purchase->product->category->name ?? 'N/A',
                'product_image' => $purchase->product->image,
                'total' => $purchase->total,
                'received_at' => $purchase->received_at
            ];
        }
        
        return response()->json($formatted);
    }

    // 🔥 NEW: Refresh all pending purchase prices to use current cost_price from products
    public function refreshPrices()
    {
        try {
            $purchases = Purchase::where('status', 'pending')->get();
            $updated = 0;
            
            foreach ($purchases as $purchase) {
                $product = Product::find($purchase->product_id);
                if ($product && $product->cost_price && $product->cost_price > 0) {
                    // Update with the correct cost price
                    $purchase->price = $product->cost_price;
                    $purchase->total = $product->cost_price * $purchase->quantity;
                    $purchase->save();
                    $updated++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Successfully refreshed {$updated} purchase record(s)",
                'updated' => $updated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}