<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class RaceConditionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_race_condition_prevents_overselling(): void
    {
        $product = Product::first();
        $initialStock = $product->inventory->stock;
        $totalRequests = 100;

        $mh = curl_multi_init();
        $handles = [];

        for ($i = 0; $i < $totalRequests; $i++) {
            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL            => 'http://127.0.0.1:8000/api/orders',
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => json_encode([
                    'customer_name' => 'Customer ' . $i,
                    'items' => [
                        [
                            'product_id' => $product->id,
                            'quantity'   => 1,
                        ]
                    ]
                ]),
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
                CURLOPT_RETURNTRANSFER => true,
            ]);

            curl_multi_add_handle($mh, $ch);
            $handles[] = $ch;
        }

        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);

        $successCount = 0;
        foreach ($handles as $ch) {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode === 201) $successCount++;
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }
        curl_multi_close($mh);

        $finalStock = $product->inventory->fresh()->stock;

        $failCount = $totalRequests - $successCount;

        dump([
            'initial_stock'    => $initialStock,
            'total_requests'   => $totalRequests,
            'success_orders'   => $successCount,
            'failed_orders'    => $failCount,
            'final_stock'      => $finalStock,
        ]);

        $this->assertGreaterThanOrEqual(0, $finalStock);
        $this->assertEquals($initialStock - $finalStock, $successCount);
    }
}
