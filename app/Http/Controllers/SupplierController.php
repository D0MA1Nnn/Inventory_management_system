<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index()
    {
        return Supplier::all();
    }

    public function show($id)
    {
        return Supplier::findOrFail($id);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'contact_number' => 'required|string|max:20',
                'address' => 'required|string',
                'products_offered' => 'nullable|string',
                'email' => 'nullable|email',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            $path = null;
            
            if ($request->hasFile('image')) {
                // Store the image in public/suppliers folder
                $path = $request->file('image')->store('suppliers', 'public');
            }

            $supplier = Supplier::create([
                'name' => $request->name,
                'contact_number' => $request->contact_number,
                'address' => $request->address,
                'products_offered' => $request->products_offered,
                'email' => $request->email,
                'image' => $path
            ]);

            return response()->json($supplier, 201);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            
            $data = [
                'name' => $request->name,
                'contact_number' => $request->contact_number,
                'address' => $request->address,
                'products_offered' => $request->products_offered,
                'email' => $request->email
            ];
            
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($supplier->image && Storage::disk('public')->exists($supplier->image)) {
                    Storage::disk('public')->delete($supplier->image);
                }
                $data['image'] = $request->file('image')->store('suppliers', 'public');
            }
            
            $supplier->update($data);
            return response()->json($supplier);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            
            // Delete image if exists
            if ($supplier->image && Storage::disk('public')->exists($supplier->image)) {
                Storage::disk('public')->delete($supplier->image);
            }
            
            $supplier->delete();
            return response()->json(['message' => 'Supplier deleted successfully']);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}