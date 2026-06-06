<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('inventory')->get();
            return response()->json(['data' => $products]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve products', 'error' => $e->getMessage()], 400);
        }
    }

    public function store(ProductRequest $request)
    {
        try {
            $product = Product::create($request->validated());
            return response()->json(['data' => $product, 'message' => 'Product created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create product', 'error' => $e->getMessage()], 400);
        }
    }
}
