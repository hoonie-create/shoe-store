@extends('layouts.user')

@section('content')
<div class="container mx-auto px-8 py-10 flex flex-col items-center justify-center min-h-screen">
    <div class="text-center mb-6">
        <h2 class="text-3xl font-black uppercase italic tracking-tighter">PEMBAYARAN PESANAN</h2>
        <p class="text-gray-400 text-xs tracking-widest uppercase mt-1">SEGERA SELESAIKAN TRANSAKSI ANDA</p>
    </div>

    <div class="bg-white w-full max-w-xl rounded-[2.5rem] p-10 shadow-2xl border border-gray-50 text-center space-y-8">
        
        @if($order->payment_method === 'BANK')
            {{-- Interface Bank Transfer --}}
            <div>
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">NOMOR VIRTUAL ACCOUNT (BCA)</p>
                <div class="bg-[#f9f9f9] rounded-2xl p-5 flex justify-between items-center border border-gray-50">
                    <span class="text-2xl font-black tracking-wider text-black">8802 9182 3310</span>
                    <button class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-black transition">SALIN</button>
                </div>
            </div>
        @else
            {{-- Interface QRIS Dinamis dengan Barcode Mockup --}}
            <div>
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">SCAN QRIS UNTUK MEMBAYAR</p>
                <div class="bg-[#f9f9f9] p-6 rounded-3xl w-48 h-48 mx-auto flex items-center justify-center border border-gray-100 shadow-inner">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=LuxeStep-{{ $order->invoice }}" alt="QRIS Barcode" class="w-full h-full object-contain">
                </div>
            </div>
        @endif

        <div>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">TOTAL YANG HARUS DIBAYAR</p>
            <h3 class="text-4xl font-black text-blue-600 italic">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h3>
        </div>

        <p class="text-[10px] text-gray-400 font-medium italic leading-relaxed px-4">
            *Pesanan akan otomatis dibatalkan jika pembayaran tidak diterima dalam 24 jam. Barang di keranjang akan otomatis dipindahkan ke riwayat pesanan setelah konfirmasi.
        </p>

        {{-- Form Upload Bukti Fisik --}}
        <form action="{{ route('payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data" class="pt-4 border-t border-gray-50 space-y-4">
            @csrf
            <div class="text-left space-y-2">
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Unggah Bukti Transfer (.jpg / .png)</label>
                <input type="file" name="payment_proof" required class="block w-full bg-[#f9f9f9] text-xs font-bold rounded-xl p-3 outline-none text-gray-500 border border-dashed border-gray-200">
            </div>

            <button type="submit" class="w-full bg-black text-white py-5 rounded-2xl font-black text-xs tracking-[0.15em] uppercase hover:bg-gray-800 transition-all shadow-xl shadow-black/10">
                SAYA SUDAH MELAKUKAN PEMBAYARAN
            </button>
        </form>

        <a href="{{ route('cart.index') }}" class="inline-block text-[9px] font-black text-gray-300 hover:text-black uppercase tracking-widest transition pt-2">
            KEMBALI KE KERANJANG
        </a>
    </div>
</div>
@endsection