<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Fitur Pencarian Akun
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        // Mengambil data user terdaftar dan membaginya ke dalam halaman (Pagination)
        $users = $query->latest()->paginate(10);

        // Menuju file view Anda: resources/views/admin/user.blade.php
        return view('admin.user', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:user,admin'
        ]);

        $user = User::findOrFail($id);
        
        // Proteksi agar admin tidak mengubah rolenya sendiri secara tidak sengaja
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa mengubah role akun Anda sendiri!');
        }

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', "Aturan akses untuk {$user->name} berhasil diperbarui!");
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        // Proteksi agar admin tidak menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun yang sedang Anda gunakan!');
        }

        $user->delete();

        return redirect()->back()->with('success', "Akun {$user->name} berhasil dihapus secara permanen!");
    }
}