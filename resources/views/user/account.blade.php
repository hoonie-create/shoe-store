@extends('layouts.user')

@section('content')
<div class="px-8 pb-10 bg-white min-h-screen">
    {{-- Notifikasi Sukses/Error --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-600 rounded-2xl font-bold text-[10px] uppercase tracking-widest border border-green-100">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-2xl font-bold text-[10px] uppercase tracking-widest border border-red-100">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
        </div>
    @endif

    <div class="border-b pb-4 mb-10 mt-4">
        <h2 class="text-2xl font-black uppercase italic tracking-tighter">Profil Saya</h2>
        <p class="text-gray-400 text-xs mt-1">Kelola informasi pribadi dan keamanan akun Anda.</p>
    </div>

    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            {{-- Bagian Foto Profil (SINKRON DATA UPLOAD & AVATAR DINAMIS) --}}
            <div class="flex flex-col items-center justify-start border-r border-gray-50 pr-12">
                <form action="{{ route('account.updateFoto') }}" method="POST" enctype="multipart/form-data" id="fotoForm" class="text-center">
                    @csrf
                    <div class="relative group cursor-pointer w-40 h-40 mx-auto" onclick="document.getElementById('inputFoto').click()">
                        {{-- Jika User Punya Foto Asli di Storage --}}
                        @if(Auth::user()->foto)
                            <img src="{{ asset('storage/' . Auth::user()->foto) }}" 
                                 class="w-40 h-40 object-cover rounded-full border-8 border-gray-50 shadow-2xl">
                        @else
                            {{-- Jika Kosong, Tampilkan Inisial Nama Akun Otomatis --}}
                            <div class="w-40 h-40 bg-black text-white rounded-full flex items-center justify-center text-5xl font-black shadow-2xl italic border-8 border-gray-50">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        @endif

                        {{-- Efek Hover Kamera Menarik --}}
                        <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <i class="fas fa-camera text-white text-2xl"></i>
                        </div>
                    </div>
                    
                    {{-- Input Berkas Tersembunyi --}}
                    <input type="file" name="foto" id="inputFoto" class="hidden" onchange="document.getElementById('fotoForm').submit()">
                    
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mt-6 cursor-pointer hover:text-black transition" onclick="document.getElementById('inputFoto').click()">
                        Klik Foto Untuk Ganti
                    </p>
                </form>
            </div>

            {{-- Form Edit Profil (Data Input Berubah Mengikuti Sesi Login Akun) --}}
            <div class="lg:col-span-2">
                <form action="{{ route('account.update') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ Auth::user()->name }}" required
                                   class="block w-full bg-[#f9f9f9] border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-black outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Alamat Email</label>
                            <input type="email" name="email" value="{{ Auth::user()->email }}" required
                                   class="block w-full bg-[#f9f9f9] border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-black outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Role</label>
                        <div class="flex items-center gap-3">
                            <span class="bg-black text-white px-6 py-2 rounded-full text-[10px] font-black uppercase italic tracking-widest">
                                {{ Auth::user()->role }}
                            </span>
                            <p class="text-[9px] text-gray-300 italic">*Role tidak dapat diubah secara mandiri</p>
                        </div>
                    </div>

                    <div class="pt-6 flex gap-4">
                        <button type="submit" class="bg-black text-white px-10 py-4 rounded-2xl font-black text-[10px] tracking-widest uppercase hover:bg-gray-800 transition shadow-xl">
                            Simpan Perubahan
                        </button>
                        
                        {{-- Tombol untuk memicu Modal Ubah Password --}}
                        <button type="button" onclick="document.getElementById('passwordModal').classList.remove('hidden')" 
                                class="border-2 border-gray-100 px-10 py-4 rounded-2xl font-black text-[10px] tracking-widest uppercase hover:border-black transition">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL UBAH PASSWORD --}}
<div id="passwordModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-xl font-black uppercase italic tracking-tighter">Ubah Password</h3>
            <button onclick="document.getElementById('passwordModal').classList.add('hidden')" class="text-gray-400 hover:text-black">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('account.password') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Password Lama</label>
                <input type="password" name="current_password" required class="block w-full bg-[#f9f9f9] border-none rounded-xl p-4 text-sm font-bold focus:ring-2 focus:ring-black outline-none">
            </div>
            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Password Baru</label>
                <input type="password" name="new_password" required class="block w-full bg-[#f9f9f9] border-none rounded-xl p-4 text-sm font-bold focus:ring-2 focus:ring-black outline-none">
            </div>
            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" required class="block w-full bg-[#f9f9f9] border-none rounded-xl p-4 text-sm font-bold focus:ring-2 focus:ring-black outline-none">
            </div>
            
            <button type="submit" class="w-full bg-black text-white py-4 rounded-xl font-black text-[10px] tracking-widest uppercase hover:bg-gray-800 transition mt-4">
                Update Password
            </button>
        </form>
    </div>
</div>
@endsection