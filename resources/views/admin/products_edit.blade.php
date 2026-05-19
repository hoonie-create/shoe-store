@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-extrabold tracking-tight text-black uppercase italic">Edit Produk</h2>
            <p class="text-gray-500 text-sm">Perbarui informasi koleksi sneakers LuxeStep.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-sm font-bold text-gray-400 hover:text-black transition uppercase tracking-widest">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Product Name</label>
                    <input type="text" name="nama_produk" value="{{ $product->nama_produk }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-black outline-none transition" required>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Price (IDR)</label>
                    <input type="number" name="harga" value="{{ $product->harga }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-black outline-none transition" required>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Stock</label>
                    <input type="number" name="stok" value="{{ $product->stok }}" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-black outline-none transition" required>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Size</label>
                    <input type="text" name="ukuran" value="{{ $product->ukuran }}" placeholder="Contoh: 38, 39, 40" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-black outline-none transition" required>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Description Product</label>
                <textarea name="deskripsi" rows="4" 
                          class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-black outline-none transition">{{ $product->deskripsi }}</textarea>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Product Picture</label>
                <div class="flex items-center gap-6 p-4 border-2 border-dashed border-gray-100 rounded-2xl">
                    <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-50 border border-gray-100">
                        <img src="{{ asset('storage/' . $product->foto) }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <input type="file" name="foto" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-black file:text-white hover:file:bg-gray-800 cursor-pointer">
                        <p class="mt-2 text-xs text-gray-400 italic">*Biarkan kosong jika tidak ingin mengubah foto.</p>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-50 flex gap-3">
                <button type="submit" class="flex-1 bg-black text-white py-4 rounded-2xl font-bold uppercase tracking-widest text-xs shadow-xl hover:bg-gray-900 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.products.index') }}" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-2xl font-bold uppercase tracking-widest text-xs text-center hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection