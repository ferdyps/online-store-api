<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequest;
use App\Models\Inventory;
use App\Models\Product;

class InventoryController extends Controller
{
    public function store(InventoryRequest $request, Product $product)
    {
        try {
            $validated = $request->validated();

            $inventory = Inventory::updateOrCreate(
                ['product_id' => $product->id],
                ['stock' => $validated['stock']]
            );

            return response()->json(['data' => $inventory, 'message' => 'Inventory updated successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update inventory', 'error' => $e->getMessage()], 400);
        }
    }
}
