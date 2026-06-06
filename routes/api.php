<?php

use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('products', ProductController::class)->only(['index', 'store']);
Route::post('products/{product}/inventories', [InventoryController::class, 'store']);
Route::apiResource('orders', OrderController::class)->only(['store', 'show']);
