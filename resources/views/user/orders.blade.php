@extends('layouts.user')

@section('content')
<div class="px-8 pb-10 bg-white min-h-screen">
    {{-- Notifikasi Sukses/Error Flash --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-600 rounded-2xl font-bold text-[10px] uppercase tracking-widest border border-green-100 mt-4">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="border-b pb-4 mb-10 mt-4">
        <h2 class="text-2xl font-black uppercase italic tracking-tighter">Riwayat Pesanan Anda</h2>
        <p class="text-gray-400 text-xs mt-1">Pantau status perjalanan sneakers impianmu di sini.</p>
    </div>

    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-400 text-[10px] uppercase tracking-[0.2em]">
                    <th class="py-5 px-6 font-black">No. Invoice</th>
                    <th class="py-5 px-6 font-black">Tanggal Pembelian</th>
                    <th class="py-5 px-6 font-black">Produk</th>
                    <th class="py-5 px-6 font-black">Total Bayar</th>
                    <th class="py-5 px-6 font-black">Status</th>
                    <th class="py-5 px-6 font-black text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-xs">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50/50 transition">
                    {{-- No. Invoice --}}
                    <td class="py-6 px-6 font-black text-black italic">#{{ $order->invoice }}</td>
                    
                    {{-- Tanggal --}}
                    <td class="py-6 px-6 text-gray-400 font-bold">
                        {{ $order->created_at->format('d M Y, H:i') }} WIB
                    </td>

                    {{-- Nama Produk & Detail Ukuran --}}
                    <td class="py-6 px-6">
                        <p class="font-black text-black uppercase italic">{{ $order->product->nama_produk ?? 'Premium Sneakers' }}</p>
                        <p class="text-[10px] text-gray-400 font-bold normal-case mt-0.5">Size: {{ $order->size }} | Qty: {{ $order->quantity }} Pcs</p>
                    </td>

                    {{-- Total Bayar --}}
                    <td class="py-6 px-6 font-black text-blue-600 italic">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>

                    {{-- TAMPILAN STATUS (Disesuaikan Agar Mendukung Semua Alur Status Resmi) --}}
                    <td class="py-6 px-6">
                        @if($order->status === 'Pending' && is_null($order->payment_proof))
                            <span class="bg-amber-50 text-amber-600 border border-amber-100 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest italic">
                                Menunggu Pembayaran
                            </span>
                        @elseif($order->status === 'Pending' && !is_null($order->payment_proof))
                            <span class="bg-purple-50 text-purple-600 border border-purple-100 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest italic animate-pulse">
                                Menunggu Verifikasi Admin
                            </span>
                        @elseif($order->status === 'Diproses')
                            <span class="bg-yellow-50 text-yellow-700 border border-yellow-100 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest italic">
                                Sedang Diproses (Packing)
                            </span>
                        @elseif($order->status === 'Dikirim')
                            <span class="bg-blue-50 text-blue-600 border border-blue-100 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest italic">
                                Dalam Pengiriman
                            </span>
                        @elseif($order->status === 'Selesai')
                            <span class="bg-green-50 text-green-600 border border-green-100 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest italic">
                                Selesai / Approved
                            </span>
                        @elseif($order->status === 'Dibatalkan')
                            <span class="bg-red-50 text-red-600 border border-red-100 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest italic">
                                Ditolak / Dibatalkan
                            </span>
                        @else
                            <span class="bg-gray-50 text-gray-500 border border-gray-100 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest italic">
                                {{ $order->status }}
                            </span>
                        @endif
                    </td>

                    {{-- TAMPILAN TOMBOL AKSI LENGKAP --}}
                    <td class="py-6 px-6 text-center">
                        @if($order->status === 'Pending' && is_null($order->payment_proof))
                            {{-- Jika belum bayar, arahkan ke halaman upload bukti --}}
                            <a href="{{ route('payment.page', $order->id) }}" class="inline-block bg-black text-white text-[9px] font-black px-4 py-2 rounded-xl uppercase tracking-widest hover:bg-gray-800 transition shadow-md">
                                Bayar Sekarang
                            </a>
                        @elseif($order->status === 'Pending' && !is_null($order->payment_proof))
                            {{-- Menunggu review admin --}}
                            <span class="text-[10px] text-purple-600 font-black uppercase tracking-wider italic"><i class="fas fa-clock"></i> In Review</span>
                        @elseif($order->status === 'Diproses' || $order->status === 'Dikirim' || $order->status === 'Selesai')
                            {{-- Jika sudah di-approve, user berhak melihat struk belanja resmi --}}
                            <a href="{{ route('payment.success', $order->id) }}" class="inline-block bg-green-600 text-white text-[9px] font-black px-4 py-2 rounded-xl uppercase tracking-widest hover:bg-green-700 transition shadow-md">
                                <i class="fas fa-file-invoice mr-1"></i> Lihat Struk
                            </a>
                        @else
                            <span class="text-[10px] text-red-500 font-black uppercase tracking-wider italic"><i class="fas fa-times-circle"></i> No Action</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-20 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-shopping-bag text-4xl text-gray-100 mb-4"></i>
                            <p class="text-gray-400 font-black uppercase tracking-widest text-xs">Belum ada riwayat pesanan</p>
                            <a href="{{ route('home') }}" class="mt-4 text-xs font-black border-b-2 border-black pb-1 hover:text-gray-500 hover:border-gray-500 transition">MULAI BELANJA</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer Support Card --}}
    <div class="mt-8 flex items-center gap-4 bg-[#f9f9f9] p-6 rounded-2xl border border-gray-100">
        <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center shadow-md">
            <i class="fas fa-headset"></i>
        </div>
        <div>
            <h4 class="text-sm font-black uppercase italic tracking-tight text-black">Butuh bantuan dengan pesanan Anda?</h4>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Hubungi customer service kami yang tersedia 24/7.</p>
        </div>
        <button class="ml-auto bg-white border border-gray-200 px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-black hover:text-white transition shadow-sm">
            HUBUNGI KAMI
        </button>
    </div>
</div>
@endsection