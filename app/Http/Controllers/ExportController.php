<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;



class ExportController extends Controller
{
    public function exportPDF($market_id, $category_id , Request $request)
    {
        $params = [
            'market_id' => $market_id,
            'category_id' => $category_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        $response = Http::get(env('API_BASE_URL') . "/prices", $params);
    $prices = $response->successful() ? $response->json() : [];

        $pdf = Pdf::loadView('exports.prices_pdf', compact('prices'));

        return $pdf->download('data_harga_pasar.pdf');
    }
}
