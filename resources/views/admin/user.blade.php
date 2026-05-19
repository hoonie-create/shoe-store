@extends('layouts.admin')

@section('content')
<div class="px-8 pb-10 bg-white min-h-screen mt-4">
    
    {{-- Notifikasi Status Sukses/Gagal --}}
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

    {{-- Header Banner --}}
    <div class="bg-black rounded-[2.5rem] p-12 mb-10 text-white shadow-2xl relative overflow-hidden">
        <div>
            <h2 class="text-4xl font-black mb-2 uppercase italic tracking-tighter">Manajemen User</h2>
            <p class="opacity-60 font-medium text-sm italic">Memonitor semua akun terdaftar dan kelola hak akses sistem LuxeStep.</p>
        </div>
    </div>

    {{-- Form Pencarian --}}
    <div class="bg-white p-6 rounded-[2rem] mb-10 border border-gray-100 shadow-sm">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama pengguna atau alamat email..." 
                       class="w-full bg-[#f9f9f9] border-none rounded-xl py-4 pl-12 pr-6 text-xs font-bold outline-none transition-all">
            </div>
            <button type="submit" class="bg-black text-white px-8 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-800 transition">
                Cari User
            </button>
        </form>
    </div>

    {{-- Tabel Pengguna --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[10px] text-gray-400 uppercase tracking-wider bg-gray-50/50 border-b border-gray-100">
                    <th class="p-8">Nama Akun & Email</th>
                    <th class="p-8">Tanggal Registrasi</th>
                    <th class="p-8">Status Role</th>
                    <th class="p-8">Ubah Role</th>
                    <th class="p-8 text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody class="text-xs font-bold text-black divide-y divide-gray-50">
                @forelse($users as $user)
                <tr>
                    <td class="p-8">
                        <div>
                            <p class="text-sm font-black uppercase italic tracking-tight">{{ $user->name }}</p>
                            <p class="text-gray-400 font-medium normal-case mt-0.5">{{ $user->email }}</p>
                        </div>
                    </td>
                    <td class="p-8 text-gray-400 font-medium">
                        {{ $user->created_at->format('d M Y, H:i') }}
                    </td>
                    <td class="p-8">
                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase border {{ $user->role === 'admin' ? 'bg-black text-white border-black' : 'bg-gray-100 text-gray-500' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="p-8">
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <select name="role" class="bg-[#f9f9f9] rounded-xl px-3 py-2 text-[10px] font-black outline-none cursor-pointer">
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>USER</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>ADMIN</option>
                                </select>
                                <button type="submit" class="bg-black text-white p-2 rounded-xl hover:bg-gray-800 transition">
                                    <i class="fas fa-save text-[10px]"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-[10px] text-gray-300 font-medium italic">Sedang Anda Gunakan</span>
                        @endif
                    </td>
                    <td class="p-8 text-center">
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 transition">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        @else
                            <i class="fas fa-lock text-gray-200 text-xs"></i>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-16 text-gray-400 italic">Tidak ada user terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="p-8 border-t border-gray-100 bg-gray-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection