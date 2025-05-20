<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage; 
use App\Models\Market;
use Illuminate\Support\Facades\Log;

class MarketController extends Controller
{
    protected $apiUrl = 'http://localhost:8080/api/markets';

    public function index(Request $request)
    {
        $search = $request->input('search');

        $response = Http::get($this->apiUrl, [
            'search' => $search
        ]);

        if ($response->successful()) {
            $body = $response->json();
            $markets = isset($body['markets']) ? $body['markets'] : $body;

        } else {
            Log::error('Gagal mengambil data pasar dari API: ' . $response->body());
            $markets = [];
        }

        return view('market.index', compact('markets', 'search'));
    }

    public function create()
    {
        return view('market.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $imagePath = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/markets'), $filename);
                $imagePath = 'images/markets/' . $filename;
            }

            $response = Http::post($this->apiUrl, [
                'name' => $request->input('name'),
                'location' => $request->input('location'),
                'image_url' => $imagePath,
            ]);

            if ($response->status() === 409) {
                return redirect()->back()->withErrors(['name' => $response->json('error')])->withInput();
            }
            
            if ($response->failed()) {
                throw new \Exception("Gagal menambahkan pasar. Response: " . $response->body());
            }
            

            return redirect()->route('market.index')->with('success', 'Pasar berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error("Error saat menambahkan pasar: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan pasar. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        $response = Http::get("$this->apiUrl/$id");

        if ($response->failed()) {
            return redirect()->route('market.index')->with('error', 'Gagal mengambil data pasar.');
        }

        $market = $response->json();
        return view('market.edit', compact('market'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $data = [
                'name' => $request->input('name'),
                'location' => $request->input('location'),
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/markets'), $filename);
                $imagePath = 'images/markets/' . $filename;


                $existingMarket = Http::get("$this->apiUrl/$id")->json();
                if (!empty($existingMarket['image_url'])) {
                    Storage::disk('public')->delete($existingMarket['image_url']);
                }

                $data['image_url'] = $imagePath;
            }

            $response = Http::put("$this->apiUrl/$id", $data);

            if ($response->failed()) {
                throw new \Exception("Gagal memperbarui pasar. Response: " . $response->body());
            }

            
            return redirect()->route('market.index')->with('success', 'Pasar berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error("Error saat memperbarui pasar: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui pasar.');
        }
    }

    public function destroy($id)
{
    $response = Http::delete("$this->apiUrl/$id");

    if ($response->failed()) {
        return response()->json(['message' => 'Gagal menghapus pasar.'], 500);
    }

    return response()->json(['message' => 'Pasar berhasil dihapus']);
}
    public function editLocation($id)
    {
        $response = Http::get("$this->apiUrl/$id");

        if ($response->failed()) {
            return redirect()->route('market.index')->with('error', 'Gagal mengambil data pasar.');
        }

        $marketData = $response->json();
        $market = (object) $marketData;

        return view('market.location', compact('market'));
    }

    public function updateLocation(Request $request, $id)
    {
        Log::info('Request updateLocation received:', $request->all());

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        try {
            $market = Market::findOrFail($id);

            $market->update([
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            ]);

            Log::info('Sending request to API:', [
                'url' => "$this->apiUrl/$id/location",
                'data' => [
                    'latitude' => $request->input('latitude'),
                    'longitude' => $request->input('longitude'),
                ]
            ]);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->put("$this->apiUrl/$id/location", [
                'latitude' => floatval($request->input('latitude')),
                'longitude' => floatval($request->input('longitude')),
            ]);

            Log::info('Response from API:', ['status' => $response->status(), 'body' => $response->body()]);

            if ($response->failed()) {
                throw new \Exception("Gagal memperbarui lokasi di API. Response: " . $response->body());
            }

            return redirect()->route('market.index')->with('success', 'Lokasi pasar berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error("Error saat memperbarui lokasi pasar: " . $e->getMessage());
            return redirect()->route('market.index')->with('error', 'Terjadi kesalahan saat memperbarui lokasi pasar.');
        }
    }
}
