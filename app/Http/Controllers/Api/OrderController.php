<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(OrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();

            $order = Order::create(['customer_name' => $validated['customer_name']]);

            $totalPrice = 0;

            foreach ($validated['items'] as $item) {
                $inventory = Inventory::where('product_id', $item['product_id'])->lockForUpdate()->first();

                if (!$inventory || $inventory->stock < $item['quantity']) {
                    throw new \Exception('Insufficient stock for product ID: ' . $item['product_id']);
                }

                $inventory->decrement('stock', $item['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $inventory->product->price_flash_sale,
                ]);

                $totalPrice += $inventory->product->price_flash_sale  * $item['quantity'];
            }

            $order->update(['total_price' => $totalPrice]);

            DB::commit();
            return response()->json(['data' => $order->load('orderItems.product')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create order', 'details' => $e->getMessage()], 400);
        }
    }

    public function show(Order $order)
    {
        try {
            $order->load('orderItems.product');
            return response()->json(['data' => $order]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve order', 'details' => $e->getMessage()], 400);
        }
    }
}
