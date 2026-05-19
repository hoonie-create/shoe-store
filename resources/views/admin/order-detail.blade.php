@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    
    {{-- NAVIGASI & TOMBOL AKSI UTAMA --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 no-print">
        <a href="{{ route('admin.dashboard') }}" class="text-xs font-black text-gray-400 hover:text-black uppercase tracking-widest transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        
        <button onclick="window.print()" class="w-full sm:w-auto bg-black hover:bg-gray-800 text-white font-black text-xs uppercase tracking-widest px-6 py-3.5 rounded-xl shadow-md transition flex items-center justify-center gap-2">
            <i class="fas fa-print"></i> PRINT SHIPPING LABEL (RESI)
        </button>
    </div>

    {{-- KARTU LABEL PENGIRIMAN FORMAL (SHIPPING LABEL FORMAT) --}}
    <div class="bg-white border-2 border-black p-8 text-black relative id-card-print">
        
        {{-- HEADER RESI & WATERMARK EXPEDISI TOKO --}}
        <div class="flex justify-between items-center border-b-4 border-black pb-4 mb-4">
            <div>
                <h1 class="text-3xl font-black tracking-tighter uppercase italic text-black">LUXESTEP LOGISTICS</h1>
                <p class="text-[10px] tracking-widest font-black uppercase text-gray-500">STANDARD DELIVERY LABEL</p>
            </div>
            {{-- Simulasi Barcode --}}
            <div class="flex flex-col items-end">
                <div class="flex items-center gap-[2px] h-10 bg-black p-1 rounded">
                    <div class="w-1 h-full bg-white"></div><div class="w-[3px] h-full bg-white"></div><div class="w-1 h-full bg-white"></div><div class="w-[2px] h-full bg-white"></div><div class="w-[4px] h-full bg-white"></div><div class="w-1 h-full bg-white"></div>
                </div>
                <span class="text-[9px] font-mono font-black mt-1">INTERNAL SCAN ONLY</span>
            </div>
        </div>

        {{-- GRID UTAMA: DATA INVOICE & METODE LOGISTIK --}}
        <div class="grid grid-cols-3 border-b-2 border-black divide-x-2 divide-black text-center mb-4 uppercase">
            <div class="p-3">
                <p class="text-[9px] font-black text-gray-400 tracking-wider">NOMOR INVOICE</p>
                <p class="text-xs font-mono font-black mt-1 tracking-wide">#{{ $order->invoice }}</p>
            </div>
            <div class="p-3">
                <p class="text-[9px] font-black text-gray-400 tracking-wider">METODE BAYAR</p>
                <p class="text-xs font-black mt-1 italic">{{ $order->payment_method ?? 'CASH/BANK' }}</p>
            </div>
            <div class="p-3 bg-gray-50">
                <p class="text-[9px] font-black text-gray-400 tracking-wider">BERAT ESTIMASI</p>
                <p class="text-xs font-black mt-1">1.00 KG (SNEAKERS)</p>
            </div>
        </div>

        {{-- BLOK UTAMA PENGIRIMAN (ALAMAT BESAR KURIR) --}}
        <div class="border-2 border-black p-5 rounded-none mb-6">
            <div class="border-b border-gray-200 pb-3 mb-3">
                <span class="bg-black text-white px-3 py-1 text-[9px] font-black uppercase tracking-widest">PENERIMA / SHIP TO</span>
                <h3 class="text-xl font-black uppercase tracking-tight text-black mt-3">{{ $order->user->name }}</h3>
                <p class="text-sm font-black font-mono tracking-wide text-gray-700 mt-1"><i class="fas fa-phone-alt text-xs mr-1 text-black"></i> {{ $order->phone ?? '-' }}</p>
            </div>
            <div>
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-wider block mb-1">ALAMAT PENGIRIMAN LENGKAP</span>
                <p class="text-sm font-bold text-black leading-relaxed uppercase italic">
                    {{ $order->address }}
                </p>
            </div>
        </div>

        {{-- DAFTAR ISI PAKET (DESAIN MANIFEST BARANG GUDANG) --}}
        <div class="border-t-2 border-dashed border-black pt-4 mb-6">
            <span class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] block mb-3">DESKRIPSI MANIFEST BARANG</span>
            <table class="w-full text-left text-xs border-2 border-black">
                <thead>
                    <tr class="bg-black text-white text-[10px] font-black uppercase tracking-wider">
                        <th class="p-3">NAMA PRODUK SNEAKERS</th>
                        <th class="p-3 text-center">SIZE</th>
                        <th class="p-3 text-center">QTY</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-black font-black uppercase">
                    <tr>
                        <td class="p-3 italic">
                            {{ $order->product->nama_produk ?? 'Premium Collection Shoes' }}
                        </td>
                        <td class="p-3 text-center font-mono text-sm bg-gray-50">{{ $order->size ?? 'N/A' }}</td>
                        <td class="p-3 text-center text-sm">{{ $order->quantity ?? 1 }} PAIR</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- REKAPITULASI BIAYA & GARIS PACKING GUDANG --}}
        <div class="border-t-2 border-black pt-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">WAKTU CETAK RESI</p>
                <p class="text-[11px] font-bold font-mono text-gray-600 mt-0.5">{{ date('d-m-Y H:i:s') }} WIB</p>
            </div>
            <div class="text-right w-full sm:w-auto bg-gray-50 border border-black p-4 min-w-[250px]">
                <div class="flex justify-between text-[11px] font-bold text-gray-500 mb-1">
                    <span>TOTAL COD / TAGIHAN:</span>
                    <span class="text-black font-mono font-black">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- FOOTER STRUK / BATAS POTONG PEREKAT PAKET DUS --}}
        <div class="mt-8 pt-4 border-t-2 border-dashed border-gray-300 text-center text-[9px] font-black uppercase text-gray-400 tracking-[0.22em] no-print">
            ✂️ GUNTING DI SINI UNTUK TEMPEL PADA KARDUS BOX SEPATU
        </div>

        {{-- BUKTI TRANSFER UNTUK ARSIP LAYAR (Otomatis Hilang Saat Print) --}}
        <div class="mt-8 pt-8 border-t border-gray-100 no-print">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                <i class="fas fa-image"></i> LAMPIRAN AUDIT BUKTI TRANSFER (ADMIN ONLY)
            </h4>
            @if($order->payment_proof)
                <div class="border border-gray-100 rounded-2xl p-2 bg-gray-50 inline-block">
                    <img src="{{ asset('storage/' . $order->payment_proof) }}" class="max-w-xs h-auto rounded-xl shadow-sm object-contain mx-auto">
                </div>
            @else
                <p class="text-xs font-bold text-red-500 italic">User belum melampirkan berkas bukti fisik.</p>
            @endif
        </div>

    </div>
</div>

{{-- CONFIGURATION CSS PRINT PREVIEW FIX --}}
<style>
/* CSS Reset Saat Tampilan Monitor Normal */
.id-card-print {
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
}

/* Override Struktur Layout Utama Saat Mode Print Kertas Aktif */
@media print {
    /* Sembunyikan Sidebar Admin, Header, Tombol, Form, & Lampiran Foto */
    aside, 
    nav, 
    header,
    .no-print, 
    [class*="sidebar"], 
    [class*="nav"],
    button,
    img { 
        display: none !important; 
    }
    
    /* Paksa Background Putih Bersih Guna Menghemat Tinta Printer */
    body, html { 
        background: #ffffff !important; 
        color: #000000 !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* Bongkar Grid Flexbox Bawaan Layout Agar Tidak Mengkerut Menjadi Kolom Kecil */
    main, 
    .flex-1,
    .container,
    div {
        box-shadow: none !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        display: block !important;
    }

    /* Atur Box Utama Label Mengisi Lembar Kertas Secara Proporsional */
    .id-card-print { 
        box-shadow: none !important; 
        border: 3px solid #000000 !important; 
        padding: 25px !important; 
        width: 100% !important; 
        max-width: 100% !important;
        margin-top: 0 !important;
    }
    
    .id-card-print table {
        border: 2px solid #000000 !important;
    }
    
    .id-card-print th {
        background-color: #000000 !important;
        color: #ffffff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>
@endsection