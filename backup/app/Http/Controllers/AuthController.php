<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'instansi' => 'required|string|max:255',
            'role' => 'required|in:Admin,Operator,Pimpinan',
            'password' => 'required|string|min:6|confirmed',
        ]);

        \App\Models\User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'instansi' => $request->instansi,
            'role' => $request->role,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'status' => 'Pending',
            'tanggal_daftar' => now(),
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan tunggu aktivasi dari Admin.');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required|captcha'
        ], [
            'captcha.captcha' => 'Kode captcha yang dimasukkan salah.',
            'captcha.required' => 'Verifikasi captcha wajib diisi.'
        ]);

        $credentials = $request->only('email', 'password');

        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            $user = \Illuminate\Support\Facades\Auth::user();
            
            // Check if user is Admin
            if ($user->role !== 'Admin') {
                \Illuminate\Support\Facades\Auth::logout();
                return back()->withErrors([
                    'email' => 'Hanya Admin yang dapat melakukan login.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            
            // Generate mock JWT token for decoding demo
            $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
            $payload = base64_encode(json_encode([
                'id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email,
                'role' => $user->role,
                'iat' => time(),
                'exp' => time() + 3600
            ]));
            $token = str_replace(['+', '/', '='], ['-', '_', ''], $header) . "." . 
                     str_replace(['+', '/', '='], ['-', '_', ''], $payload) . ".signature";

            // Arahkan Admin langsung ke halaman dokumen dengan form upload terbuka
            $intended = session()->pull('url.intended', route('dokumen.index', ['create' => 'true']));
            return redirect($intended)->with('token', $token)->with('success', 'Login berhasil! Silakan upload dokumen.');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
