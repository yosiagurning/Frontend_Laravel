<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // Modify the index method to include pagination parameters
    public function index(Request $request)
    {
        $apiUrl = "https://go-backend-production-91cc.up.railway.app/api/prices";
        
        // Add search parameter if provided
        if ($request->has('search')) {
            $apiUrl .= "?search=" . urlencode($request->search);
        }
    
        $response = Http::get($apiUrl);
    
        if ($response->failed()) {
            return view('home.home')->with('prices', []);
        }
    
        $prices = collect($response->json())->map(function ($item) {
            return [
                'commodity_name' => $item['item_name'],
                'current_price' => $item['current_price'],
                'change_date' => $item['updated_at'],
                'market_name' => $item['market']['name'] ?? '',
                'category_name' => $item['category']['name'] ?? '',
            ];
        })->toArray();
        
        return view('home.home', compact('prices'));
    }
}
