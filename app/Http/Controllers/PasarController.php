<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PasarController extends Controller
{
    public function index()
    {
        $response = Http::get(env('GO_BACKEND_URL').'/pasar');
        $pasar = $response->json();
        return view('pasar.index', compact('pasar'));
    }

    public function create()
    {
        return view('pasar.create');
    }

    public function store(Request $request)
    {
        Http::post(env('GO_BACKEND_URL').'/pasar', [
            'lokasi' => $request->lokasi,
        ]);
        return redirect()->route('pasar.index');
    }

    public function edit($id)
    {
        $response = Http::get(env('GO_BACKEND_URL')."/pasar/$id");
        $pasar = $response->json();
        return view('pasar.edit', compact('pasar'));
    }

    public function update(Request $request, $id)
    {
        Http::put(env('GO_BACKEND_URL')."/pasar/$id", [
            'lokasi' => $request->lokasi,
        ]);
        return redirect()->route('pasar.index');
    }

    public function destroy($id)
    {
        Http::delete(env('GO_BACKEND_URL')."/pasar/$id");
        return redirect()->route('pasar.index');
    }
}

