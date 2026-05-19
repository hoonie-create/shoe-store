@extends('layouts.admin')

@section('content')
<div class="px-8 pb-10 bg-white min-h-screen mt-4">
    
    {{-- Notifikasi Status Sukses/Error dari Sistem --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-600 rounded-2xl font-bold text-[10px] uppercase tracking-widest border border-green-100">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-2xl font-bold text-[10px] uppercase tracking-widest border border-red-100">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-2xl font-bold text-[10px] uppercase tracking-widest border border-red-100">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- Header Banner Pengaturan Akun Admin --}}
    <div class="bg-black rounded-[2.5rem] p-12 mb-10 text-white shadow-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-4xl font-black mb-2 uppercase italic tracking-tighter">Pengaturan Akun Admin</h2>
            <p class="opacity-60 font-medium text-sm italic">Kelola kredensial sistem utama dan hak akses otoritas Administrator LuxeStep.</p>
        </div>
        <i class="fas fa-user-shield absolute -right-10 -bottom-10 text-white/5 text-[15rem]"></i>
    </div>

    {{-- Box Kontainer Utama Pengaturan Data Profil --}}
    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            {{-- Sisi Kiri: Inisial Gambar & Status Protektor Root --}}
            <div class="flex flex-col items-center justify-center border-r border-gray-50 pr-12 text-center">
                {{-- Render 2 Huruf Pertama dari Nama Admin yang Sedang Login --}}
                <div class="w-40 h-40 bg-black text-white rounded-full flex items-center justify-center text-5xl font-black shadow-2xl italic border-8 border-gray-50 mb-6">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="bg-red-50 text-red-600 border border-red-100 rounded-xl px-4 py-2 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-xs animate-pulse"></i>
                    <span class="text-[9px] font-black uppercase tracking-wider">Root Access Active</span>
                </div>
            </div>

            {{-- Sisi Kanan: Form Data Kredensial Admin --}}
            <div class="lg:col-span-2 flex flex-col justify-center">
                <form action="{{ route('admin.account.update') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Input Nama Admin --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Administrator</label>
                            <input type="text" name="name" value="{{ Auth::user()->name }}" required
                                   class="block w-full bg-[#f9f9f9] border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-black outline-none transition-all text-black">
                        </div>
                        {{-- Input Email Admin --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Sistem Utama</label>
                            <input type="email" name="email" value="{{ Auth::user()->email }}" required
                                   class="block w-full bg-[#f9f9f9] border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-black outline-none transition-all text-black">
                        </div>
                    </div>

                    {{-- Label Status Otoritas Tingkat Role --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tingkat Otoritas (Role)</label>
                        <div class="flex items-center gap-3">
                            <span class="bg-black text-white px-6 py-2 rounded-full text-[10px] font-black uppercase italic tracking-widest">
                                {{ strtoupper(Auth::user()->role) }}
                            </span>
                            <p class="text-[9px] text-gray-400 italic">Memiliki kendali penuh terhadap pangkalan data produk, order logistik, dan rekap finansial.</p>
                        </div>
                    </div>

                    {{-- Tombol Tindakan Aksi --}}
                    <div class="pt-6 flex gap-4">
                        <button type="submit" class="bg-black text-white px-10 py-4 rounded-2xl font-black text-[10px] tracking-widest uppercase hover:bg-gray-800 transition shadow-xl">
                            Perbarui Profil Admin
                        </button>
                        
                        {{-- Tombol Untuk Memicu Pop-Up Modal Ganti Password Admin --}}
                        <button type="button" onclick="document.getElementById('adminPasswordModal').classList.remove('hidden')" 
                                class="border-2 border-gray-100 px-10 py-4 rounded-2xl font-black text-[10px] tracking-widest uppercase hover:border-black transition text-black">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- MODAL INTERAKTIF: POP-UP GANTI PASSWORD ADMINISTRATOR SYSTEM --}}
<div id="adminPasswordModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl animate-fade-in">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-xl font-black uppercase italic tracking-tighter text-black">Ubah Password Admin</h3>
            <button onclick="document.getElementById('adminPasswordModal').classList.add('hidden')" class="text-gray-400 hover:text-black transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.account.password') }}" method="POST" class="space-y-4">
            @csrf
            {{-- Input Password Lama Admin --}}
            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Password Lama Admin</label>
                <input type="password" name="current_password" required 
                       class="block w-full bg-[#f9f9f9] border-none rounded-xl p-4 text-sm font-bold focus:ring-2 focus:ring-black outline-none text-black">
            </div>
            {{-- Input Password Baru --}}
            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Password Baru Admin</label>
                <input type="password" name="new_password" required 
                       class="block w-full bg-[#f9f9f9] border-none rounded-xl p-4 text-sm font-bold focus:ring-2 focus:ring-black outline-none text-black">
            </div>
            {{-- Input Verifikasi Konfirmasi Password --}}
            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" required 
                       class="block w-full bg-[#f9f9f9] border-none rounded-xl p-4 text-sm font-bold focus:ring-2 focus:ring-black outline-none text-black">
            </div>
            
            {{-- Tombol Eksekusi Enkripsi Hash --}}
            <button type="submit" class="w-full bg-black text-white py-4 rounded-xl font-black text-[10px] tracking-widest uppercase hover:bg-gray-800 transition mt-4 shadow-lg shadow-black/10">
                Update Password Sistem
            </button>
        </form>
    </div>
</div>
@endsection