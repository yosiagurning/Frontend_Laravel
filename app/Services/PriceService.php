<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PriceService
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_GOLANG_URL');
    }

    public function getAllPrices()
    {
        $response = Http::get("{$this->apiUrl}/prices");
        return $response->json();
    }

    public function getPriceById($id)
    {
        $response = Http::get("{$this->apiUrl}/prices/{$id}");
        return $response->json();
    }

    public function createPrice($data)
    {
        $response = Http::post("{$this->apiUrl}/prices", $data);
        return $response->json();
    }

    public function updatePrice($id, $data)
    {
        $response = Http::put("{$this->apiUrl}/prices/{$id}", $data);
        return $response->json();
    }

    public function deletePrice($id)
    {
        $response = Http::delete("{$this->apiUrl}/prices/{$id}");
        return $response->json();
    }
}
