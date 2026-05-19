@extends('layouts.user')

@section('content')
<div class="receipt-master-container">
    
    {{-- AREA KERTAS STRUK KASIR THERMAL (DIKUNCI VERTIKAL DENGAN ATURAN BORDER TEGAS) --}}
    <div class="print-card-thermal">
        
        {{-- HEADER STRUK --}}
        <div class="text-center-receipt header-section-receipt">
            <h1 class="receipt-title">LUXESTEP STORE</h1>
            <p class="receipt-subtitle">PREMIUM SNEAKERS FULFILLMENT</p>
            <p class="receipt-url">WWW.LUXESTEP.COM</p>
        </div>

        {{-- METADATA TRANSAKSI --}}
        <div class="dashed-separator-receipt">
            <div class="flex-receipt">
                <span>INVOICE  :</span>
                <span class="font-black-receipt">#{{ $order->invoice }}</span>
            </div>
            <div class="flex-receipt">
                <span>TANGGAL  :</span>
                <span>{{ $order->created_at->format('d/m/Y H:i') }} WIB</span>
            </div>
            <div class="flex-receipt">
                <span>KASIR    :</span>
                <span>LUXESTEP SYSTEM</span>
            </div>
            <div class="flex-receipt">
                <span>PELANGGAN:</span>
                <span class="font-black-receipt uppercase-receipt">{{ $order->user->name ?? 'Pelanggan LuxeStep' }}</span>
            </div>
        </div>

        {{-- DAFTAR ISI BARANG --}}
        <div class="dashed-separator-receipt">
            <p class="font-black-receipt" style="margin: 0 0 10px 0;">DETAIL PRODUK:</p>
            <div style="margin-bottom: 4px;">
                <p class="font-black-receipt italic-receipt uppercase-receipt" style="margin: 0; line-height: 1.4;">
                    {{ $order->product->nama_produk ?? 'Sneakers Premium Collection' }}
                </p>
                <div class="flex-receipt" style="color: #4b5563; margin-top: 6px; padding-left: 8px;">
                    <span>{{ $order->quantity ?? 1 }} PCS x Rp {{ number_format(($order->subtotal ?? $order->total_price) / ($order->quantity ?? 1), 0, ',', '.') }}</span>
                    <span class="font-black-receipt text-black">Rp {{ number_format($order->subtotal ?? $order->total_price, 0, ',', '.') }}</span>
                </div>
                <p style="font-size: 11px; color: #6b7280; font-weight: bold; margin: 4px 0 0 0; padding-left: 8px;">UKURAN (SIZE): {{ $order->size ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- RINCIAN TOTAL FINANSIAL KASIR --}}
        <div class="dashed-separator-receipt" style="line-height: 1.8;">
            <div class="flex-receipt">
                <span>SUBTOTAL PRODUK</span>
                <span>Rp {{ number_format($order->subtotal ?? $order->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="flex-receipt">
                <span>ONGKOS KIRIM (FLAT)</span>
                <span>Rp {{ number_format($order->shipping ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex-receipt">
                <span>PPN PEMERINTAH (11%)</span>
                <span>Rp {{ number_format($order->tax ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex-receipt total-row-receipt">
                <span>TOTAL AKHIR</span>
                <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- METODE BAYAR & STATUS LOGISTIK --}}
        <div class="payment-box-receipt">
            <div class="flex-receipt" style="font-weight: bold;">
                <span>METODE BAYAR :</span>
                <span class="italic-receipt">
                    @if($order->payment_method === 'BANK')
                        TRANSFER BANK (BCA)
                    @elseif($order->payment_method === 'QRIS')
                        QRIS AUTOMATIC SCAN
                    @else
                        {{ $order->payment_method }}
                    @endif
                </span>
            </div>
            <div class="flex-receipt" style="font-weight: bold; margin-top: 4px;">
                <span>STATUS ORDER  :</span>
                <span style="color: #2563eb; font-style: italic;">{{ $order->status }}</span>
            </div>
        </div>

        {{-- FOOTER STRUK KASIR --}}
        <div class="text-center-receipt" style="padding-top: 8px;">
            <p class="font-black-receipt" style="margin: 0 0 6px 0; letter-spacing: 0.05em;">*** LUNAS / PAID ***</p>
            <p class="footer-text-receipt">Terima kasih atas kepercayaan Anda berbelanja di LuxeStep.</p>
        </div>

    </div>

    {{-- TOMBOL OPERASIONAL MONITOR (Otomatis Hilang Saat Print) --}}
    <div class="no-print-area">
        <button onclick="window.print()" class="btn-receipt-action btn-secondary-receipt">
            <i class="fas fa-print"></i> CETAK STRUK
        </button>
        <a href="{{ route('orders.index') }}" class="btn-receipt-action btn-primary-receipt">
            MY ORDERS &rarr;
        </a>
    </div>

</div>

{{-- ── MONITOR & MEDIA PRINT CSS ── --}}
<style>
/* Desain dasar kontainer utama di monitor browser */
.receipt-master-container {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    min-height: 100vh !important;
    padding: 50px 16px !important;
    background-color: #f3f4f6 !important;
    box-sizing: border-box !important;
}

/* Desain boks kertas struk di monitor */
.print-card-thermal {
    font-family: 'Courier New', Courier, monospace !important;
    background-color: #ffffff !important;
    color: #000000 !important;
    padding: 35px 30px !important;
    border: 1px solid #d1d5db !important;
    width: 100% !important;
    max-width: 420px !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    box-sizing: border-box !important;
    border-radius: 8px !important;
}

/* Generator garis putus-putus hitam tegas yang dipaksa muncul saat cetak */
.dashed-separator-receipt {
    border-bottom: 1px dashed #000000 !important;
    padding-bottom: 14px !important;
    margin-bottom: 16px !important;
    box-sizing: border-box !important;
}

.header-section-receipt {
    border-bottom: 1px dashed #000000 !important;
    padding-bottom: 14px !important;
    margin-bottom: 16px !important;
}

.flex-receipt {
    display: flex !important;
    flex-direction: row !important;
    justify-content: space-between !important;
    width: 100% !important;
    box-sizing: border-box !important;
}

.text-center-receipt {
    text-align: center !important;
}

.receipt-title {
    font-size: 20px !important;
    font-weight: 900 !important;
    margin: 0 !important;
    letter-spacing: -0.02em !important;
}

.receipt-subtitle {
    font-size: 10px !important;
    color: #4b5563 !important;
    font-weight: bold !important;
    margin: 4px 0 0 0 !important;
    letter-spacing: 0.05em !important;
}

.receipt-url {
    font-size: 9px !important;
    color: #6b7280 !important;
    font-weight: 500 !important;
    margin: 2px 0 0 0 !important;
}

.font-black-receipt {
    font-weight: bold !important;
    color: #000000 !important;
}

.italic-receipt {
    font-style: italic !important;
}

.uppercase-receipt {
    text-transform: uppercase !important;
}

.total-row-receipt {
    margin-top: 10px !important;
    padding-top: 10px !important;
    border-top: 1px dotted #4b5563 !important;
    font-weight: bold !important;
    font-size: 15px !important;
    color: #000000 !important;
}

.payment-box-receipt {
    background-color: #f9fafb !important;
    border: 1px solid #e5e7eb !important;
    padding: 14px !important;
    margin-bottom: 16px !important;
    font-size: 11px !important;
}

.footer-text-receipt {
    font-size: 10px !important;
    color: #4b5563 !important;
    font-style: italic !important;
    margin: 0 !important;
    line-height: 1.5 !important;
}

/* Area tombol di monitor */
.no-print-area {
    display: flex !important;
    gap: 16px !important;
    width: 100% !important;
    max-width: 420px !important;
    margin-top: 24px !important;
    box-sizing: border-box !important;
}

.btn-receipt-action {
    flex: 1 !important;
    padding: 14px 0 !important;
    border-radius: 12px !important;
    font-weight: 900 !important;
    font-size: 11px !important;
    letter-spacing: 0.1em !important;
    text-transform: uppercase !important;
    text-align: center !important;
    text-decoration: none !important;
    cursor: pointer !important;
    transition: all 0.2s !important;
    border: none !important;
}

.btn-secondary-receipt {
    background-color: #e5e7eb !important;
    color: #000000 !important;
    border: 1px solid #d1d5db !important;
}

.btn-secondary-receipt:hover {
    background-color: #d1d5db !important;
}

.btn-primary-receipt {
    background-color: #000000 !important;
    color: #ffffff !important;
}

.btn-primary-receipt:hover {
    background-color: #1f2937 !important;
}

/* =========================================================
   ── FIX TOTAL MEDIA PRINT MODE (SINKRONISASI 100% SAMA) ──
   ========================================================= */
@media print {
    /* 1. Sembunyikan elemen dashboard / navigasi di luar kertas */
    aside, nav, header, footer, .no-print-area, [class*="sidebar"], [class*="nav"], .no-print { 
        display: none !important; 
    }
    
    /* 2. Paksa halaman cetak menjadi putih polos mutlak */
    body, html { 
        background: #ffffff !important; 
        color: #000000 !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        /* Mengaktifkan pemaksaan render grafik warna & garis putus-putus browser */
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* 3. Bobol dan netralkan struktur flexbox parent bawaan app layout website */
    main, .flex-1, .container, .receipt-master-container, div {
        box-shadow: none !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* 4. KUNCI DIMENSI BOX: Memaksa bentuk boks struk cetak sama persis seperti di monitor */
    .print-card-thermal { 
        box-shadow: none !important; 
        border: 1px solid #000000 !important; /* Membuat garis tepi boks luar tetap tercetak jelas */
        padding: 30px 25px !important; 
        width: 400px !important; /* Mempertahankan lebar ideal struk kasir vertikal */
        margin: 30px auto !important; /* Membuat posisi jatuh pas di tengah lembaran cetak browser */
        display: block !important;
        background: #ffffff !important;
        border-radius: 0px !important;
    }

    /* 5. Paksa garis putus-putus pembatas segmen kasir agar tercetak hitam legam */
    .dashed-separator-receipt, .header-section-receipt {
        border-bottom: 1px dashed #000000 !important;
        display: block !important;
        width: 100% !important;
    }

    /* 6. Jaga kestabilan baris flex agar nominal harga tetap rata kanan-kiri (tidak turun/bergeser) */
    .flex-receipt {
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        width: 100% !important;
    }

    .text-center-receipt {
        text-align: center !important;
    }

    .payment-box-receipt {
        background-color: #f3f4f6 !important;
        border: 1px solid #000000 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .total-row-receipt {
        border-top: 1px dotted #000000 !important;
    }
}
</style>
@endsection