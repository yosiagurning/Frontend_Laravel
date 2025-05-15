<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class PriceExport implements FromCollection
{
    protected $market_id;
    protected $category_id;

    public function __construct($market_id, $category_id)
    {
        $this->market_id = $market_id;
        $this->category_id = $category_id;
        $this->request = $request;
    }

    public function collection()
    {
        $apiUrl = env('API_BASE_URL') . '/prices?market_id=' . $this->market_id . '&category_id=' . $this->category_id;

        $response = Http::get($apiUrl);

        $prices = $response->successful() ? $response->json() : [];

        return collect($prices)->map(function ($price) {
            return [
                'Nama Barang' => $price['item_name'],
                'Harga Awal' => $price['initial_price'],
                'Harga Sekarang' => $price['current_price'],
                'Persentase Perubahan' => round($price['change_percent'], 2) . '%',
                'Alasan Perubahan' => $price['reason'],
                'Tanggal Diperbarui' => $price['updated_at'],
            ];
        });
        
        $response = Http::get($apiUrl, array_merge([
            'market_id' => $this->market_id,
            'category_id' => $this->category_id,
        ], $this->request->only(['start_date', 'end_date'])));
        
    }
}
