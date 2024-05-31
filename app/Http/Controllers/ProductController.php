<?php

namespace App\Http\ProductControllers;
use App\Models\Post;
use Illuminate\Http\Request;

abstract class ProductController
{
    public function index()
    {
        $products = Product::with('user')->get();
        return response()->json($products);
    }

    public function store(Request $request){
        
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);
    
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->user_id = auth()->user()->id;
        $product->save();
        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->save();
        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }

    public function edit($id){
        $product - Product::where('id', $id)->firstOrFail();
        $user = auth()-user();
        return response()->json([$product, 200]);
    }

    public function destroy($id){
        $product - Product::where('id', $id)->firstOrFail();
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

    public function show($id){
        $product - Product::with('user')->where('id', $id)->firstOrFail();
        return response()->json([$product, 200]);
    } 
}
