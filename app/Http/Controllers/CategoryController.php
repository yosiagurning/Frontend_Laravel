<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_BASE_URL');
    }

    // Tampilkan semua kategori
    public function index(Request $request)
    {
        $search = $request->query('search');

        $response = Http::get("{$this->apiBaseUrl}/categories");

        if ($response->failed()) {
            return back()->with('error', 'Gagal mengambil data kategori.');
        }

        $categories = $response->json();

        if ($search) {
            $categories = array_filter($categories, function ($category) use ($search) {
                return stripos($category['name'], $search) !== false;
            });
        }


        return view('categories.index', compact('categories'));
    }

    // Form tambah kategori
    public function create()
    {
        $markets = Http::get("{$this->apiBaseUrl}/markets")->json();
        return view('categories.create', compact('markets'));
    }

    // Simpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post("{$this->apiBaseUrl}/categories", [
            'name' => $request->name,
            'description' => $request->description,
            'market_ids' => array_map('intval', $request->market_ids ?? []),
        ]);        
            

        logger('Store Category Response:', $response->json());


        if ($response->failed()) {
            $message = $response->json('error') ?? 'Gagal menambahkan kategori.';
            return back()->withErrors(['name' => $message])->withInput();
        }
        

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Form edit kategori
    public function edit($id)
    {
        $categoryResponse = Http::get("{$this->apiBaseUrl}/categories/{$id}");
        $marketResponse = Http::get("{$this->apiBaseUrl}/markets");
    
        if ($categoryResponse->failed() || $marketResponse->failed()) {
            return back()->with('error', 'Gagal mengambil data kategori atau pasar.');
        }
    
        $category = $categoryResponse->json();
        $markets = $marketResponse->json();
        $selectedMarkets = $category['market_ids'] ?? [];
    
        return view('categories.edit', compact('category', 'markets', 'selectedMarkets'));
    }
    

    // Update kategori
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'market_ids' => 'array',
            'market_ids.*' => 'numeric',
        ]);
    
        \Log::debug('Update Category Payload', [
            'name' => $request->name,
            'description' => $request->description,
            'market_ids' => $request->market_ids,
        ]);
    
        $marketIDs = array_map('intval', $request->market_ids ?? []);
        \Log::debug('Parsed MarketIDs (Laravel):', $marketIDs); // ✅ Gantilah ini
    
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->put("{$this->apiBaseUrl}/categories/{$id}", [
            'name' => $request->name,
            'description' => $request->description,
            'market_ids' => $marketIDs,
        ]);
    
        logger('Store Category Response:', $response->json());
    
        if ($response->failed()) {
            $message = $response->json('error') ?? 'Gagal memperbarui kategori.';
            return back()->withErrors(['name' => $message])->withInput();
        }
        
    
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }
    

    // Hapus kategori
    public function destroy($id)
{
    $response = Http::delete("{$this->apiBaseUrl}/categories/{$id}");

    if ($response->failed()) {
        $errorMessage = 'Gagal menghapus kategori.';
        if ($response->status() === 409) {
            $errorMessage = 'Kategori tidak dapat dihapus karena masih digunakan oleh barang lain.';
        }
        return back()->with('error', $errorMessage);
    }

    return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
}
}
