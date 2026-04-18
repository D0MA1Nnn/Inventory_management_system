<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        return view('sales');
    }

    public function getSales()
    {
        $sales = Sale::with(['product', 'product.category', 'user'])
            ->orderBy('sold_at', 'desc')
            ->get();
        
        return response()->json($sales);
    }

    public function getSalesStats()
    {
        $stats = [
            'total_sales' => Sale::where('status', 'completed')->sum('total_price'),
            'total_orders' => Sale::where('status', 'completed')->count(),
            'total_products_sold' => Sale::where('status', 'completed')->sum('quantity'),
            'average_order_value' => Sale::where('status', 'completed')->avg('total_price') ?? 0,
            'today_sales' => Sale::whereDate('sold_at', today())->sum('total_price'),
            'this_month_sales' => Sale::whereMonth('sold_at', now()->month)->sum('total_price'),
            'pending_orders' => Sale::where('status', 'pending')->count(),
        ];
        
        return response()->json($stats);
    }

    public function getSalesByCategory()
    {
        $salesByCategory = Sale::with('product.category')
            ->where('status', 'completed')
            ->get()
            ->groupBy(function($sale) {
                return $sale->product->category->name ?? 'Uncategorized';
            })
            ->map(function($items) {
                return [
                    'total' => $items->sum('total_price'),
                    'quantity' => $items->sum('quantity'),
                    'count' => $items->count()
                ];
            });
        
        return response()->json($salesByCategory);
    }

    public function getRecentSales()
    {
        $sales = Sale::with(['product', 'product.category', 'user'])
            ->orderBy('sold_at', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json($sales);
    }

    public function store(Request $request)
    {
        try {
            // Log the request for debugging
            \Log::info('Sale request received:', $request->all());
            
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'customer_name' => 'nullable|string|max:255',
                'customer_email' => 'nullable|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'payment_method' => 'required|in:cash,card,bank_transfer',
            ]);

            $product = Product::find($request->product_id);

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            if ($product->quantity < $request->quantity) {
                return response()->json(['error' => 'Insufficient stock. Only ' . $product->quantity . ' left'], 400);
            }

            $totalPrice = $product->price * $request->quantity;

            // Get the authenticated user ID (for admin sales) or use a default
            $userId = auth()->id();
            if (!$userId) {
                // If no authenticated user (customer checkout), use the first admin or a default
                $firstUser = \App\Models\User::first();
                $userId = $firstUser ? $firstUser->id : 1;
            }

            $sale = Sale::create([
                'product_id' => $request->product_id,
                'user_id' => $userId,
                'quantity' => $request->quantity,
                'unit_price' => $product->price,
                'total_price' => $totalPrice,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'notes' => $request->notes,
                'sold_at' => now(),
            ]);

            // Update product stock
            $product->quantity -= $request->quantity;
            $product->save();

            return response()->json(['success' => true, 'sale' => $sale]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Sale error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to process sale: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $sale = Sale::findOrFail($id);
            
            // Return stock to product if cancelled
            if ($sale->status === 'completed') {
                $product = Product::find($sale->product_id);
                if ($product) {
                    $product->quantity += $sale->quantity;
                    $product->save();
                }
            }
            
            $sale->delete();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}