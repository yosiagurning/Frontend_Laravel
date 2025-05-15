<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data dari API backend yang benar
        $response = Http::get('http://localhost:8081/api/dashboard-data');

        if ($response->failed()) {
            return view('dashboard', [
                'totalCommodities' => 0,
                'totalInventoryValue' => 0,
                'filteredPrices' => [],
                'timeRange' => 'monthly',
                'error' => 'Gagal mengambil data dashboard',
            ]);
        }
        

        $data = $response->json();

        // // Validasi apakah data dari API lengkap
        // if (!isset($data['total_commodities'], $data['total_stock_value'], $data['price_changes'])) {
        //     return view('dashboard', [
        //         'totalCommodities' => 0,
        //         'totalInventoryValue' => 0,
        //         'filteredPrices' => [],
        //         'timeRange' => 'monthly',
        //         'error' => 'Data dashboard tidak lengkap',
        //     ]);
        // }
        

        $totalCommodities = $data['total_commodities'];
        $totalInventoryValue = $data['total_stock_value'];
        $prices = $data['price_changes'];

        // Ambil rentang waktu dari request
        $timeRange = $request->query('time_range', 'monthly'); // Default bulanan

        // Filter data harga berdasarkan rentang waktu
        $filteredPrices = $this->filterPricesByTimeRange($prices, $timeRange);

        return view('dashboard', compact('totalCommodities', 'totalInventoryValue', 'filteredPrices', 'timeRange'));
    }

    private function filterPricesByTimeRange($prices, $timeRange)
    {
        $now = Carbon::now();
        $timeLimit = match ($timeRange) {
            'weekly' => $now->copy()->subWeek(),
            'monthly' => $now->copy()->subMonth(),
            'yearly' => $now->copy()->subYear(),
            default => $now->copy()->subMonth(),
        };

        if (!is_array($prices)) {
            return []; // atau bisa juga: return $prices ?? [];
        }
        

        return array_filter($prices, function ($price) use ($timeLimit) {
            return Carbon::parse($price['change_date'])->greaterThanOrEqualTo($timeLimit);
        });
    }
}
