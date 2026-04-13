<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductPageController extends Controller
{
    public function index()
    {
        return Product::with('category')->get();
    }

    public function show($id)
    {
        return Product::with('category')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Get dynamic fields from request (all fields except the standard ones)
        $dynamicFields = [];
        $standardFields = ['name', 'brand', 'model_number', 'price', 'quantity', 'category_id', 'image', 'performance', '_method', '_token'];
        
        foreach ($request->all() as $key => $value) {
            if (!in_array($key, $standardFields) && !is_null($value) && $value !== '') {
                $dynamicFields[$key] = $value;
            }
        }

        $product = Product::create([
            'name' => $request->name,
            'brand' => $request->brand,
            'model_number' => $request->model_number,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'performance' => $request->performance,
            'dynamic_fields' => $dynamicFields,
        ]);

        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Get dynamic fields from request
        $dynamicFields = [];
        $standardFields = ['name', 'brand', 'model_number', 'price', 'quantity', 'category_id', 'image', 'performance', '_method', '_token'];
        
        foreach ($request->all() as $key => $value) {
            if (!in_array($key, $standardFields) && !is_null($value) && $value !== '') {
                $dynamicFields[$key] = $value;
            }
        }

        $product->update([
            'name' => $request->name,
            'brand' => $request->brand,
            'model_number' => $request->model_number,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'performance' => $request->performance,
            'dynamic_fields' => $dynamicFields,
        ]);

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return response()->json(['success' => true]);
    }
}