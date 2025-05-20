<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncController extends Controller
{
    protected $apiBase;

    public function __construct()
    {
        $this->apiBase = env('API_BASE_URL'); // Contoh: http://localhost:8080/api
    }

    public function syncData()
    {
        try {
            $response = Http::get("{$this->apiBase}/sync");
            
            if ($response->successful()) {
                return redirect()->back()->with('success', 'Data berhasil disinkronkan antara barang dan harga.');
            } else {
                Log::error('Gagal sinkronisasi data: ' . $response->body());
                return redirect()->back()->with('error', 'Gagal sinkronisasi data: ' . ($response->json()['error'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            Log::error('Exception saat sinkronisasi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
