<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input sebelum dikirim ke backend
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6'
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        try {
            // Kirim request ke backend Golang
            $response = Http::timeout(5)
            ->withHeaders(['Accept' => 'application/json'])
            ->post('https://go-backend-production-91cc.up.railway.app/api/login', [
                'username' => $request->username,
                'password' => $request->password,
            ]);


            // Periksa apakah login berhasil
            if ($response->successful()) {
                $data = $response->json();

                // Simpan token dan user ke session
                session([
                    'token' => $data['token'],
                    'user'  => $data['user']
                ]);

                return redirect()->route('dashboard')->with('success', 'Login berhasil!');
            }

            // Jika respons API tidak sukses
            return back()->with('error', 'Login gagal! Username atau password salah.');
        
        } catch (\Exception $e) {
            // Jika terjadi kesalahan pada request
            return back()->with('error', 'Terjadi kesalahan saat menghubungi server. Coba lagi nanti.');
        }
    }

    // Logout user
    public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/'); // 👈 Redirect ke home
}
}
