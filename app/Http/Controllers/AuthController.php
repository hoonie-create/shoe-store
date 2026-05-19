<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan Halaman Login Khusus
    public function showLogin() {
        return view('auth.login');
    }

    // Menampilkan Halaman Register Khusus
    public function showRegister() {
        return view('auth.register');
    }

    // Proses Register: Semua akun baru otomatis jadi 'user'
    public function processRegister(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Otomatis menjadi user
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Proses Login: Memisahkan Admin, User, dan Memberikan Peringatan Error Spesifik
    public function processLogin(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
        ]);

        // 1. Cari data user berdasarkan email terlebih dahulu
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // 2. Jika email terdaftar, periksa apakah password salah
            if (!Hash::check($request->password, $user->password)) {
                // SINKRONISASI FORM: Melempar error spesifik ke key 'password' agar border input login otomatis jadi merah!
                return back()->withErrors([
                    'password' => 'Password yang Anda masukkan salah.',
                ])->withInput($request->only('email'));
            }

            // 3. Jika password benar, lakukan login
            Auth::login($user, $request->has('remember'));
            $request->session()->regenerate();

            // Logika Pengalihan (Redirect) Berdasarkan Hak Akses (Role)
            if (Auth::user()->role == 'admin') {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect()->intended('/home');
        }

        // 4. Jika email benar-benar tidak terdaftar di sistem database
        return back()->withErrors([
            'email' => 'Email yang Anda masukkan tidak terdaftar pada sistem kami.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}