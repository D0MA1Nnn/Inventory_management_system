<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductPageController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
        
        return $query->get();
    }

    public function store(Request $request)
    {
        $path = null;
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
        }
        
        $product = Product::create([
            'name' => $request->name,
            'brand' => $request->brand,
            'model_number' => $request->model_number,
            'architecture_socket' => $request->architecture_socket,
            'core_configuration' => $request->core_configuration,
            'performance' => $request->performance,
            'integrated_graphics' => $request->integrated_graphics,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'image' => $path
        ]);
        
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $data = [
            'name' => $request->name,
            'brand' => $request->brand,
            'model_number' => $request->model_number,
            'architecture_socket' => $request->architecture_socket,
            'core_configuration' => $request->core_configuration,
            'performance' => $request->performance,
            'integrated_graphics' => $request->integrated_graphics,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id
        ];
        
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        
        $product->update($data);
        return response()->json($product);
    }

    public function show($id)
    {
        return Product::with('category')->findOrFail($id);
    }
    
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return response()->json(['success' => true]);
    }
}