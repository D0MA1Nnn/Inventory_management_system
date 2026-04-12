<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('products')->get();
        
        $suppliers = $suppliers->map(function($supplier) {
            return [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'contact_number' => $supplier->contact_number,
                'address' => $supplier->address,
                'email' => $supplier->email,
                'image' => $supplier->image,
                'created_at' => $supplier->created_at,
                'updated_at' => $supplier->updated_at,
                'products_offered' => $supplier->products->pluck('id')->toArray(),
                'products' => $supplier->products
            ];
        });
        
        return response()->json($suppliers);
    }

    public function show($id)
    {
        $supplier = Supplier::with('products')->findOrFail($id);
        
        $transformed = [
            'id' => $supplier->id,
            'name' => $supplier->name,
            'contact_number' => $supplier->contact_number,
            'address' => $supplier->address,
            'email' => $supplier->email,
            'image' => $supplier->image,
            'created_at' => $supplier->created_at,
            'updated_at' => $supplier->updated_at,
            'products_offered' => $supplier->products->pluck('id')->toArray(),
            'products' => $supplier->products
        ];
        
        return response()->json($transformed);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'contact_number' => 'required',
            'address' => 'required',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('suppliers', 'public');
        }

        $supplier = Supplier::create([
            'name' => $request->name,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'email' => $request->email,
            'image' => $imagePath,
        ]);

        // SAVE PRODUCTS TO PIVOT TABLE
        if ($request->products_offered) {
            $productIds = json_decode($request->products_offered, true);
            if (is_array($productIds) && count($productIds) > 0) {
                $supplier->products()->sync($productIds);
                \Log::info('Products saved for supplier: ' . $supplier->id, ['product_ids' => $productIds]);
            }
        }

        $supplier->load('products');
        $transformed = [
            'id' => $supplier->id,
            'name' => $supplier->name,
            'contact_number' => $supplier->contact_number,
            'address' => $supplier->address,
            'email' => $supplier->email,
            'image' => $supplier->image,
            'created_at' => $supplier->created_at,
            'updated_at' => $supplier->updated_at,
            'products_offered' => $supplier->products->pluck('id')->toArray(),
            'products' => $supplier->products
        ];

        return response()->json($transformed);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $imagePath = $supplier->image;

        if ($request->hasFile('image')) {
            if ($supplier->image) {
                Storage::disk('public')->delete($supplier->image);
            }
            $imagePath = $request->file('image')->store('suppliers', 'public');
        }

        $supplier->update([
            'name' => $request->name,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'email' => $request->email,
            'image' => $imagePath,
        ]);

        // UPDATE PIVOT TABLE
        if ($request->products_offered) {
            $productIds = json_decode($request->products_offered, true);
            if (is_array($productIds)) {
                $supplier->products()->sync($productIds);
                \Log::info('Products updated for supplier: ' . $supplier->id, ['product_ids' => $productIds]);
            }
        } else {
            $supplier->products()->detach();
            \Log::info('Products cleared for supplier: ' . $supplier->id);
        }

        $supplier->load('products');
        $transformed = [
            'id' => $supplier->id,
            'name' => $supplier->name,
            'contact_number' => $supplier->contact_number,
            'address' => $supplier->address,
            'email' => $supplier->email,
            'image' => $supplier->image,
            'created_at' => $supplier->created_at,
            'updated_at' => $supplier->updated_at,
            'products_offered' => $supplier->products->pluck('id')->toArray(),
            'products' => $supplier->products
        ];

        return response()->json($transformed);
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->products()->detach();

        if ($supplier->image) {
            Storage::disk('public')->delete($supplier->image);
        }

        $supplier->delete();

        return response()->json(['success' => true]);
    }
}