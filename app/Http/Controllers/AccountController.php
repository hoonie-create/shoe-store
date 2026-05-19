<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Wajib ditambahkan untuk manajemen file foto
use App\Models\User;

class AccountController extends Controller
{
    // Menampilkan halaman profil
    public function index()
    {
        $user = Auth::user();
        
        // Kembalikan ke folder user.account jika filenya ada di dalam folder user
        return view('user.account', compact('user'));
    }

    // Update Nama dan Email
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    // FITUR TAMBAHAN: Update / Unggah Foto Profil Dinamis
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Batas maksimal berkas 2MB
        ]);

        $user = Auth::user();

        if ($request->hasFile('foto')) {
            // Hapus foto lama dari penyimpanan lokal jika ada dan bukan gambar bawaan default
            if ($user->foto && $user->foto !== 'default.png') {
                Storage::delete('public/' . $user->foto);
            }

            // Menyimpan file foto baru ke dalam folder storage/app/public/avatars
            $path = $request->file('foto')->store('avatars', 'public');
            
            // Perbarui nama file di kolom database tabel users
            $user->update([
                'foto' => $path
            ]);
        }

        return redirect()->back()->with('success', 'Foto profil LuxeStep Anda berhasil diperbarui!');
    }

    // Update Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Menggunakan library Hash untuk memverifikasi kecocokan password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }
}