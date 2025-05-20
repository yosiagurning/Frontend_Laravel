<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Officer;
use Illuminate\Support\Facades\Http;
use App\Models\Market;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;


class OfficerController extends Controller
{
    // Ambil data petugas dari backend Golang dan simpan ke database Laravel
    public function syncOfficers()
    {
        try {
            // Ambil data dari backend Golang
            $response = Http::get('http://localhost:8080/api/officers');

            if ($response->failed()) {
                return response()->json(['error' => 'Gagal mengambil data dari backend'], 500);
            }

            $officers = $response->json();

            foreach ($officers as $officerData) {
                Officer::updateOrCreate(
                    ['nik' => $officerData['nik']], // Pastikan tidak ada duplikasi NIK
                    [
                        'name'      => $officerData['name'],
                        'phone'     => $officerData['phone'],
                        'image_url' => $officerData['image_url'],
                        'market_id' => $officerData['market_id'],
                    ]
                );
            }

            return response()->json(['message' => 'Data petugas berhasil disinkronisasi']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    

    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $officers = Officer::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc') 
        ->get();
    
        return view('officers.index', compact('officers'));
    }
    


    public function toggleStatus($id)
    {
        $officer = Officer::findOrFail($id);
        $officer->is_active = !$officer->is_active;
        $officer->save();

        return redirect()->route('officers.index')->with('success', 'Status petugas diperbarui.');
    }

    // Tampilkan daftar petugas yang tersimpan di Laravel
    public function getOfficers()
    {
        $officers = Officer::all();
        return response()->json($officers);
        
    }
    // Simpan petugas ke database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'      => 'required|string|max:255',
            'nik'       => 'required|digits:16',
            'phone'     => 'required|regex:/^[0-9]{11,15}$/',
            'username'  => 'required|string|min:5',
            'password'  => 'required|string|min:8',
            'market_id' => 'required|exists:markets,id',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/officers'), $filename);
            $imagePath = 'images/officers/' . $filename;
        }
    
        // Payload ke backend Go
        $payload = [
            'name'      => $validatedData['name'],
            'nik'       => $validatedData['nik'],
            'phone'     => $validatedData['phone'],
            'market_id' => (int) $validatedData['market_id'],
            'username'  => $validatedData['username'],
            'password'  => $request->input('password'),
            'image_url' => $imagePath ?? '',
            'is_active' => true
        ];
    
        $response = Http::post('http://localhost:8080/api/market-officers', $payload);
    
        if ($response->failed()) {
            $error = $response->json('error') ?? 'Gagal menambahkan petugas ke backend Golang.';
            return back()->with('error', $error)->withInput();
        }
    
        return redirect()->route('officers.index')->with('success', 'Petugas berhasil ditambahkan.');
    }
    
    
    
    
    // Tampilkan form edit petugas
    public function edit($id)
    {
        $officer = Officer::findOrFail($id);
        $markets = Market::all();
        return view('officers.edit', compact('officer', 'markets'));
    }
    
    // Update data petugas
    public function update(Request $request, Officer $officer)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'nik' => 'required|digits_between:8,20',
        'phone' => 'required|numeric',
        'username' => 'required|string|min:5|unique:market_officers,username,' . $officer->id,
        'password' => 'nullable|string|min:8',
        'market_id' => 'required|exists:markets,id',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $filename = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('images/officers'), $filename);
        $validatedData['image_url'] = 'images/officers/' . $filename;

    }

    if (!empty($validatedData['password'])) {
        $validatedData['password'] = bcrypt($validatedData['password']);
    } else {
        unset($validatedData['password']);
    }

    $officer->update($validatedData);

    return redirect()->route('officers.index')->with('success', 'Petugas berhasil diperbarui.');
}

    

// Hapus petugas
public function destroy($id)
{
    try {
        $officer = Officer::findOrFail($id);
        
        // Hapus dari backend Golang
        $response = Http::delete("http://localhost:8080/api/market-officers/{$id}");
        
        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus petugas di backend Golang'
            ], 500);
        }
        
        // Hapus dari database Laravel
        $officer->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Petugas berhasil dihapus'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}


public function toggleActive($id)
{
    $officer = Officer::find($id);
    if (!$officer) {
        return response()->json(['error' => 'Petugas tidak ditemukan'], 404);
    }

    $officer->is_active = !$officer->is_active;
    $officer->save();

    return response()->json(['message' => 'Status petugas diperbarui', 'officer' => $officer]);
}
public function create()
{
    $markets = Market::all(); // Ambil data pasar untuk dropdown
    return view('officers.create', compact('markets'));
}

}
