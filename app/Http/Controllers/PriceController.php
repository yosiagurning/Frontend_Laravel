<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PriceController extends Controller
{
    protected $apiBase;

    public function __construct()
    {
        $this->apiBase = env('API_BASE_URL'); // Contoh: http://localhost:8080/api
    }

    public function index()
{
    $response = Http::get("{$this->apiBase}/markets");

    if ($response->successful()) {
        $body = $response->json();
        $markets = isset($body['markets']) ? $body['markets'] : $body;
    } else {
        $markets = [];
        Log::error("Gagal mengambil data pasar untuk halaman harga: " . $response->body());
    }

    return view('prices.index', compact('markets'));
}


public function showCategories($market_id)
{
    $response = Http::get("{$this->apiBase}/categories/market/{$market_id}");

    if ($response->failed()) {
        return back()->with('error', 'Gagal memuat kategori terkait pasar.');
    }

    $categories = $response->json();

    return view('prices.categories', compact('categories', 'market_id'));
}


    public function showPrices($market_id, $category_id, Request $request)
    {
        $params = [
            'market_id' => $market_id,
            'category_id' => $category_id,
        ];

        if ($request->filled('search')) {
            $params['search'] = $request->search;
        }
        if ($request->filled('direction')) {
            $params['direction'] = $request->direction;
        }
        if ($request->filled('range')) {
            $params['range'] = $request->range;
        }

        if ($request->filled('start_date')) {
            $params['start_date'] = $request->start_date;
        }

        if ($request->filled('end_date')) {
            $params['end_date'] = $request->end_date;
        }

        $response = Http::get("{$this->apiBase}/prices", $params);
        $prices = $response->successful() ? $response->json() : [];

        return view('prices.items', compact('prices', 'market_id', 'category_id'));
    }

    public function create($market_id, $category_id)
    {
        return view('prices.create', compact('market_id', 'category_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'initial_price' => 'required|numeric',
            'current_price' => 'required|numeric',
            'reason' => 'nullable|string|max:500',
            'market_id' => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);

        // Debug log (pastikan storage/logs/laravel.log writable)
        Log::info('Mengirim data harga ke API:', $validated);

        $payload = [
            'item_name' => $request->item_name,
            'initial_price' => (float) $request->initial_price,
            'current_price' => (float) $request->current_price,
            'reason' => $request->reason,
            'market_id' => (int) $request->market_id,
            'category_id' => (int) $request->category_id,
        ];
        
        Log::info('Payload untuk API:', $payload);
        
        $response = Http::post("{$this->apiBase}/prices", $payload);
        

        if ($response->failed()) {
            $errorMessage = $response->json()['error'] ?? 'Gagal menambahkan data harga.';
            Log::error('Gagal kirim data harga:', $response->json());

            return back()->with('error', $errorMessage);
        }

        return redirect()->route('prices.items', [$validated['market_id'], $validated['category_id']])
                         ->with('success', 'Harga berhasil ditambahkan.');
    }

    public function edit($id)
    {
        if ($id == 0) {
            return redirect()->route('prices.index')->with('error', 'ID tidak valid.');
        }

    $response = Http::get("{$this->apiBase}/prices/{$id}");

    if ($response->failed()) {
        return redirect()->route('prices.index')->with('error', 'Gagal mengambil data harga.');
    }

    $price = $response->json();
    
    return view('prices.edit', compact('price'));
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'item_name' => 'required|string|max:255',
        'initial_price' => 'required|numeric',
        'current_price' => 'required|numeric',
        'reason' => 'nullable|string|max:500',
        'market_id' => 'required|numeric',
        'category_id' => 'required|numeric',
        'item_id' => 'required|numeric',
    ]);

    Log::info("Memperbarui harga ID {$id}:", $validated);

    $payload = [
        'item_name' => $validated['item_name'],
        'initial_price' => (float) $validated['initial_price'],
        'current_price' => (float) $validated['current_price'],
        'reason' => $validated['reason'],
        'market_id' => (int) $validated['market_id'],
        'category_id' => (int) $validated['category_id'],
        'item_id' => (int) $validated['item_id'],
    ];

    $response = Http::put("{$this->apiBase}/prices/{$id}", $payload);

    if ($response->failed()) {
        $errorMessage = $response->json()['error'] ?? 'Gagal memperbarui data harga.';
        return back()->with('error', $errorMessage);
    }

    if ($response->status() == 403) {
        return back()->with('error', $response->json()['error']);
    }    

    return redirect()->route('prices.items', [$validated['market_id'], $validated['category_id']])
                     ->with('success', 'Harga berhasil diperbarui.');
}


    public function destroy($id)
    {
        $response = Http::delete("{$this->apiBase}/prices/{$id}");

        if ($response->failed()) {
            $errorMessage = $response->json()['error'] ?? 'Gagal menghapus harga.';
            return back()->with('error', $errorMessage);
        }

        return back()->with('success', 'Harga berhasil dihapus.');
    }

    public function showChart($item_id)
    {
        $response = Http::get("{$this->apiBase}/price-histories/{$item_id}");

        Log::info('Response chart (histories):', $response->json());

        $prices = $response->successful() ? $response->json() : [];

        // Ambil nama barang dari data pertama, jika ada
        $item_name = count($prices) > 0 ? $prices[0]['item_name'] : 'Tidak diketahui';

        return view('prices.chart', compact('item_name', 'prices'));
    }


    public function showCategoryChart($category_id)
    {
        $response = Http::get("{$this->apiBase}/price-histories/category/{$category_id}");
        $histories = $response->successful() ? $response->json() : [];

        $title = 'Kategori';
        return view('prices.chart-category', compact('histories', 'title'));
    }

}

