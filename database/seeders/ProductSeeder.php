<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::create([
            'name' => 'Product 1',
            'price' => 100000,
            'price_flash_sale' => 80000,
        ]);

        Inventory::create([
            'product_id' => $product->id,
            'stock' => 20,
        ]);
    }
}
