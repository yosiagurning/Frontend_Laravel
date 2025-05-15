<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        $apiBase = env('API_BASE_URL');

        // Contoh dummy data
        $prices = [
            [
                'item_name' => 'Cabai Merah',
                'initial_price' => 25000,
                'current_price' => 28000,
                'reason' => 'Ketersediaan berkurang',
                'market_id' => 1,
                'category_id' => 1,
            ],
            [
                'item_name' => 'Beras Medium',
                'initial_price' => 11000,
                'current_price' => 10500,
                'reason' => 'Panen raya',
                'market_id' => 1,
                'category_id' => 2,
            ],
        ];

        foreach ($prices as $price) {
            Http::post("$apiBase/prices", $price);
        }

        echo "✅ PriceSeeder selesai!\n";
    }
}
