@extends('layouts.admin')

@section('content')
<div class="px-4 pb-10 min-h-screen bg-white">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8 mt-4">
        <div>
            <h2 class="text-2xl font-black uppercase italic tracking-tighter text-black">Manajemen Produk</h2>
            <p class="text-gray-400 text-xs">Tambah, ubah, atau hapus koleksi sneakers LuxeStep.</p>
        </div>
        <button onclick="toggleModal('modalTambah')" class="bg-black text-white px-6 py-3 rounded-xl font-bold text-xs tracking-widest hover:bg-gray-800 transition shadow-lg uppercase cursor-pointer">
            <i class="fas fa-plus mr-2"></i> Tambah Produk
        </button>
    </div>

    {{-- Notifikasi Keberhasilan atau Galat Sistem Global --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-600 rounded-xl font-bold text-[10px] uppercase tracking-widest border border-green-100">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-xl font-bold text-[10px] uppercase tracking-widest border border-red-100 print:hidden">
            <i class="fas fa-exclamation-triangle mr-2"></i> Gagal menyimpan! Silakan periksa kembali kolom bertanda wajib isi di bawah.
        </div>
    @endif

    {{-- Tabel Manajemen Utama --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[10px] text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50 border-b border-gray-100">
                    <th class="p-8">Produk</th>
                    <th class="p-8">Harga</th>
                    <th class="p-8">Stok</th>
                    <th class="p-8 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-xs font-bold divide-y divide-gray-50">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50/50 transition text-black">
                    <td class="p-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center p-1 border border-gray-100">
                                <img src="{{ asset('storage/' . $product->foto) }}" class="w-10 object-contain max-h-full">
                            </div>
                            <div>
                                <p class="text-black uppercase font-black italic">{{ $product->nama_produk }}</p>
                                <p class="text-[10px] text-gray-400 font-bold tracking-wide mt-0.5">UKURAN: {{ $product->ukuran }} EU</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-8 text-black italic font-black text-sm">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                    <td class="p-8">
                        <span class="{{ $product->stok < 5 ? 'text-red-500 bg-red-50 px-2 py-1 rounded-md border border-red-100' : 'text-gray-500' }}">
                            {{ $product->stok }} PCS
                        </span>
                    </td>
                    <td class="p-8 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-black hover:text-white transition shadow-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini dari katalog?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-gray-100 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition shadow-sm cursor-pointer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ── MODAL COMPONENT TAMBAH PRODUK BARU ── --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-fade-in-up border border-gray-100">
        <div class="p-8 border-b flex justify-between items-center bg-gray-50/50 border-gray-100">
            <h3 class="font-black uppercase italic tracking-tighter text-black">Form Tambah Produk</h3>
            <button onclick="toggleModal('modalTambah')" class="text-gray-400 hover:text-black cursor-pointer"><i class="fas fa-times text-lg"></i></button>
        </div>
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-5">
                
                {{-- Input Nama Produk --}}
                <div class="col-span-2 space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Produk <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" 
                           class="w-full bg-[#f9f9f9] border rounded-xl p-3.5 text-sm font-bold focus:ring-2 focus:ring-black outline-none text-black @error('nama_produk') border-red-500 bg-red-50/20 focus:ring-red-500 @else border-transparent @enderror" 
                           placeholder="Contoh: Nike Air Jordan 1 Retro High">
                    @error('nama_produk')
                        <p class="text-red-500 text-[10px] font-black uppercase tracking-wider ml-1 mt-0.5"><i class="fas fa-exclamation-circle"></i> Kolom nama produk wajib diisi!</p>
                    @enderror
                </div>
                
                {{-- Input Harga Jual --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Harga Jual (Rp) <span class="text-red-500 font-bold">*</span></label>
                    <input type="number" name="harga" value="{{ old('harga') }}" 
                           class="w-full bg-[#f9f9f9] border rounded-xl p-3.5 text-sm font-bold focus:ring-2 focus:ring-black outline-none text-black @error('harga') border-red-500 bg-red-50/20 focus:ring-red-500 @else border-transparent @enderror"
                           placeholder="Contoh: 2499000">
                    @error('harga')
                        <p class="text-red-500 text-[10px] font-black uppercase tracking-wider ml-1 mt-0.5"><i class="fas fa-exclamation-circle"></i> Nominal harga jual wajib diisi!</p>
                    @enderror
                </div>
                
                {{-- Input Jumlah Stok --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Stok Unit <span class="text-red-500 font-bold">*</span></label>
                    <input type="number" name="stok" value="{{ old('stok') }}" 
                           class="w-full bg-[#f9f9f9] border rounded-xl p-3.5 text-sm font-bold focus:ring-2 focus:ring-black outline-none text-black @error('stok') border-red-500 bg-red-50/20 focus:ring-red-500 @else border-transparent @enderror"
                           placeholder="Contoh: 15">
                    @error('stok')
                        <p class="text-red-500 text-[10px] font-black uppercase tracking-wider ml-1 mt-0.5"><i class="fas fa-exclamation-circle"></i> Jumlah stok unit gudang wajib diisi!</p>
                    @enderror
                </div>
                
                {{-- Input Ukuran Sepatu --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Ukuran Sepatu <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" name="ukuran" value="{{ old('ukuran') }}" 
                           class="w-full bg-[#f9f9f9] border rounded-xl p-3.5 text-sm font-bold focus:ring-2 focus:ring-black outline-none text-black @error('ukuran') border-red-500 bg-red-50/20 focus:ring-red-500 @else border-transparent @enderror" 
                           placeholder="Contoh: 40, 41, 42, 43">
                    @error('ukuran')
                        <p class="text-red-500 text-[10px] font-black uppercase tracking-wider ml-1 mt-0.5"><i class="fas fa-exclamation-circle"></i> Spesifikasi ukuran wajib dicantumkan!</p>
                    @enderror
                </div>
                
                {{-- Input Berkas Foto --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Foto Produk Master <span class="text-red-500 font-bold">*</span></label>
                    <input type="file" name="foto" 
                           class="w-full bg-[#f9f9f9] border rounded-xl p-2.5 text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-black file:text-white hover:file:bg-gray-800 file:cursor-pointer file:transition focus:ring-2 focus:ring-black outline-none @error('foto') border-red-500 bg-red-50/20 @else border-transparent @enderror">
                    @error('foto')
                        <p class="text-red-500 text-[10px] font-black uppercase tracking-wider ml-1 mt-0.5"><i class="fas fa-exclamation-circle"></i> Berkas gambar sepatu wajib diunggah!</p>
                    @enderror
                </div>
                
                {{-- Input Deskripsi Detail --}}
                <div class="col-span-2 space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Deskripsi Detail <span class="text-red-500 font-bold">*</span></label>
                    <textarea name="deskripsi" rows="4" 
                              class="w-full bg-[#f9f9f9] border rounded-xl p-3.5 text-sm font-bold focus:ring-2 focus:ring-black outline-none text-black @error('deskripsi') border-red-500 bg-red-50/20 focus:ring-red-500 @else border-transparent @enderror"
                              placeholder="Tuliskan spesifikasi detail bahan material, nomor SKU ketersediaan, serta kelengkapan box kemasan produk...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-[10px] font-black uppercase tracking-wider ml-1 mt-0.5"><i class="fas fa-exclamation-circle"></i> Kolom deskripsi spesifikasi wajib isi dan tidak boleh kosong!</p>
                    @enderror
                </div>
            </div>
            
            {{-- Tombol Operasional Form --}}
            <div class="mt-4 flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-black text-white py-4 rounded-xl font-black text-xs tracking-[0.2em] uppercase hover:bg-gray-800 transition shadow-lg cursor-pointer">Simpan Produk</button>
                <button type="button" onclick="toggleModal('modalTambah')" class="px-8 py-4 border border-gray-200 rounded-xl font-black text-xs tracking-widest uppercase hover:bg-gray-50 transition cursor-pointer text-black">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }

    // Memaksa modal tetap terbuka otomatis jika terjadi error validasi data saat form dikirim
    document.addEventListener("DOMContentLoaded", function () {
        @if($errors->any())
            const modalTambah = document.getElementById('modalTambah');
            if (modalTambah) {
                modalTambah.classList.remove('hidden');
            }
        @endif
    });
</script>
@endsection