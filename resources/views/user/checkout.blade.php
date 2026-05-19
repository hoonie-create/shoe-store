@extends('layouts.user')

@section('content')
<div class="container mx-auto px-8 py-10 max-w-5xl">
    {{-- Header Banner Pengisian Data --}}
    <div class="border-b pb-4 mb-10 mt-4">
        <h2 class="text-2xl font-black uppercase italic tracking-tighter text-black">Detail Pengiriman & Pembayaran</h2>
        <p class="text-gray-400 text-xs mt-1">Lengkapi informasi di bawah ini untuk memproses pesanan premium Anda.</p>
    </div>

    {{-- Form Utama Checkout LuxeStep --}}
    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        @csrf
        
        {{-- Input Hidden Parameter untuk Data Tunggal (Buy Now) --}}
        <input type="hidden" name="product_id" value="{{ $product_id }}">
        <input type="hidden" name="quantity" value="{{ $quantity }}">
        <input type="hidden" name="size" value="{{ $size }}">

        {{-- FIX UTAMA: Meneruskan Array ID Keranjang Belanja Terpilih agar Bersih Sesuai Checkbox --}}
        @if(isset($selectedCartIds) && is_array($selectedCartIds))
            @foreach($selectedCartIds as $cartId)
                <input type="hidden" name="selected_cart_ids[]" value="{{ $cartId }}">
            @endforeach
        @endif

        <div class="lg:col-span-2 space-y-8">
            
            {{-- Biodata Pengiriman --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-5">
                <h3 class="text-xs font-black tracking-widest uppercase mb-2 flex items-center gap-2 text-black">
                    <i class="fas fa-map-marker-alt"></i> Informasi Alamat Tujuan
                </h3>
                
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap Penerima</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}" required 
                           class="block w-full bg-[#f9f9f9] border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-black outline-none text-black mt-1">
                </div>
                
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nomor Handphone Aktif</label>
                    <input type="text" name="phone" placeholder="Contoh: 081234567890" required 
                           class="block w-full bg-[#f9f9f9] border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-black outline-none text-black mt-1">
                </div>
                
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Alamat Rumah Lengkap</label>
                    <textarea name="address" rows="4" placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kecamatan, dan kota tujuan..." required 
                              class="block w-full bg-[#f9f9f9] border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-black outline-none text-black mt-1"></textarea>
                </div>
            </div>

            {{-- Opsi Pembayaran Interaktif Premium --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <h3 class="text-xs font-black tracking-widest uppercase mb-6 flex items-center gap-2 text-black">
                    <i class="fas fa-wallet"></i> Pilih Metode Pembayaran
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Opsi Bank Transfer --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_method" value="BANK" class="hidden peer" required>
                        <span class="flex flex-col items-center justify-center p-6 border-2 border-gray-100 rounded-2xl font-black text-xs tracking-wider peer-checked:border-black peer-checked:bg-black peer-checked:text-white text-gray-400 hover:border-gray-300 transition-all uppercase">
                            <i class="fas fa-university text-2xl mb-3"></i> BANK TRANSFER (BCA)
                        </span>
                    </label>
                    
                    {{-- Opsi QRIS --}}
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_method" value="QRIS" class="hidden peer">
                        <span class="flex flex-col items-center justify-center p-6 border-2 border-gray-100 rounded-2xl font-black text-xs tracking-wider peer-checked:border-black peer-checked:bg-black peer-checked:text-white text-gray-400 hover:border-gray-300 transition-all uppercase">
                            <i class="fas fa-qrcode text-2xl mb-3"></i> QRIS AUTOMATIC SCAN
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-gray-50 p-8 rounded-[2.5rem] border border-gray-100 sticky top-24">
                <h3 class="text-xs font-black tracking-widest uppercase mb-6 text-black">Ringkasan Pesanan</h3>
                
                {{-- Daftar Item Belanja Belanjaan --}}
                <div class="space-y-4 max-h-60 overflow-y-auto pr-2 border-b pb-6 mb-6">
                    @foreach($cartItems as $item)
                    <div class="flex items-center gap-4 bg-white p-3 rounded-2xl border border-gray-100/50 shadow-sm">
                        <img src="{{ asset('storage/' . (isset($item->product) ? $item->product->foto : $product->foto)) }}" 
                             class="w-14 h-14 object-contain bg-gray-50 rounded-xl p-1 flex-shrink-0">
                        <div class="min-w-0 flex-1">
                            <h4 class="font-black text-xs uppercase italic tracking-tight text-black truncate">
                                {{ isset($item->product) ? $item->product->nama_produk : $product->nama_produk }}
                            </h4>
                            <p class="text-[10px] text-gray-400 font-bold mt-0.5">Size: {{ $item->size }} | Qty: {{ $item->quantity }} Pcs</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Tabel Perhitungan Finansial Kompleks --}}
                <div class="space-y-3 border-b pb-4 mb-4 text-xs font-bold text-black">
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-medium">Subtotal Produk</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-medium">Biaya Pengiriman</span>
                        <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-medium">PPN Pemerintah (11%)</span>
                        <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Grand Total Final --}}
                <div class="flex justify-between items-center mb-8">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Tagihan</span>
                    <span class="text-xl font-black text-blue-600 italic">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                {{-- Tombol Submit Pembuat Invoice --}}
                <button type="submit" class="w-full bg-black text-white py-4 rounded-xl font-black text-[10px] tracking-widest uppercase hover:bg-gray-800 transition shadow-xl shadow-black/10">
                    BUAT INVOICE & BAYAR
                </button>
                
                <a href="{{ route('cart.index') }}" class="block text-center text-[9px] font-black uppercase tracking-widest text-gray-400 hover:text-black transition mt-4">
                    Kembali Ke Keranjang
                </a>
            </div>
        </div>
    </form>
</div>
@endsection