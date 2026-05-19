@extends('layouts.admin')

@section('content')
{{-- Notifikasi Keberhasilan atau Galat Sistem --}}
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

<div class="bg-black rounded-[2.5rem] p-12 mb-10 text-white shadow-2xl relative overflow-hidden">
    <div class="relative z-10">
        <h2 class="text-4xl font-black mb-2 uppercase italic tracking-tighter">Admin Dashboard</h2>
        <p class="opacity-60 font-medium text-sm">Monitoring performa toko dan pesanan masuk secara real-time.</p>
    </div>
    <i class="fas fa-shield-alt absolute -right-10 -bottom-10 text-white/5 text-[15rem]"></i>
</div>

{{-- Statistik Performa Ringkasan Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-md transition">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Total Koleksi</p>
        <h3 class="text-3xl font-black text-black italic">{{ $totalProducts }} VARIASI</h3>
    </div>
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-md transition">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Total Pesanan</p>
        <h3 class="text-3xl font-black text-black italic">{{ $totalOrders }} ORDER</h3>
    </div>
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-md transition">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Total Pengguna</p>
        <h3 class="text-3xl font-black text-black italic">{{ $totalUsers }} USER</h3>
    </div>
</div>

{{-- Panel Filter Rentang Waktu Tanggal --}}
<div class="bg-white p-10 rounded-[2.5rem] mb-10 border border-gray-100 shadow-sm">
    <h4 class="text-black font-black mb-8 flex items-center gap-3 uppercase tracking-widest italic text-sm">
        <i class="fas fa-sliders-h"></i> Filter Pesanan
    </h4>
    <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-wrap items-end gap-8">
        <div class="space-y-3">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="block w-52 bg-[#f9f9f9] border-none rounded-xl p-3 text-xs focus:ring-2 focus:ring-black outline-none font-bold text-black">
        </div>
        <div class="space-y-3">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="block w-52 bg-[#f9f9f9] border-none rounded-xl p-3 text-xs focus:ring-2 focus:ring-black outline-none font-bold text-black">
        </div>
        <div class="space-y-3">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Urutan</label>
            <select name="sort" class="block w-52 bg-[#f9f9f9] border-none rounded-xl p-3 text-xs focus:ring-2 focus:ring-black outline-none font-bold text-black">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-black text-white px-8 py-3 rounded-xl text-[10px] font-black tracking-widest hover:bg-gray-800 transition shadow-lg uppercase">Terapkan</button>
            <a href="{{ route('admin.dashboard') }}" class="bg-white text-black border border-gray-200 px-8 py-3 rounded-xl text-[10px] font-black tracking-widest hover:bg-gray-50 transition uppercase flex items-center">Reset</a>
        </div>
    </form>
</div>

{{-- ── TABEL 1: DAFTAR BUKTI PEMBAYARAN KONSUMEN (PERLU VALIDASI) ── --}}
<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden mb-12">
    <div class="p-8 border-b border-gray-50 bg-gray-50/40 flex justify-between items-center">
        <h4 class="text-black font-black uppercase italic tracking-tighter flex items-center gap-2">
            <i class="fas fa-file-invoice-dollar text-sm"></i> Daftar Bukti Pembayaran
        </h4>
        <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-4 py-1 rounded-full uppercase tracking-widest border border-blue-100">
            {{ $proofOrders->count() }} Menunggu Otorisasi
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50 border-b border-gray-50">
                    <th class="p-8">No. Invoice</th>
                    <th class="p-8">Tanggal</th>
                    <th class="p-8">Pembeli / Produk</th>
                    <th class="p-8">Total</th>
                    <th class="p-8 text-center">Bukti</th>
                    <th class="p-8 text-center">Aksi Otorisasi</th>
                </tr>
            </thead>
            <tbody class="text-xs font-bold divide-y divide-gray-50">
                @forelse($proofOrders as $proof)
                <tr class="hover:bg-gray-50/30 transition text-black">
                    <td class="p-8 italic font-black text-black">#{{ $proof->invoice }}</td>
                    <td class="p-8 text-gray-500 font-medium">{{ $proof->created_at->format('d M Y, H:i') }} WIB</td>
                    <td class="p-8">
                        <p class="uppercase font-black italic text-black">{{ $proof->user->name }}</p>
                        <p class="text-gray-400 font-bold text-[10px] normal-case mt-0.5">
                            {{ $proof->product->nama_produk ?? 'Sneakers Item' }} (Size: {{ $proof->size }})
                        </p>
                    </td>
                    <td class="p-8 font-black text-blue-600 italic">Rp {{ number_format($proof->total_price, 0, ',', '.') }}</td>
                    <td class="p-8 text-center">
                        <a href="{{ route('admin.orders.detail', $proof->id) }}" class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-black hover:text-white text-gray-600 text-[10px] font-black px-4 py-2 rounded-xl transition">
                            <i class="fas fa-eye text-[11px]"></i> Lihat Detail
                        </a>
                    </td>
                    <td class="p-8 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.payment.verify', [$proof->id, 'approve']) }}" class="bg-black text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider hover:bg-gray-800 transition shadow-sm">
                                Approve
                            </a>
                            <a href="{{ route('admin.payment.verify', [$proof->id, 'reject']) }}" class="bg-red-50 text-red-600 border border-red-100 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider hover:bg-red-100 transition">
                                Reject
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-16 text-center text-gray-400 font-medium italic bg-gray-50/20">
                        Tidak ada berkas unggahan bukti pembayaran baru yang menunggu persetujuan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── TABEL 2: DAFTAR PESANAN MASUK TOTAL ── --}}
<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/40">
        <h4 class="text-black font-black uppercase italic tracking-tighter flex items-center gap-2">
            <i class="fas fa-boxes text-sm"></i> Daftar Pesanan Masuk
        </h4>
        <span class="bg-gray-100 text-gray-500 text-[10px] font-black px-4 py-1 rounded-full uppercase tracking-widest border border-gray-200/50">LuxeStep Live Log</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50 border-b border-gray-50">
                    <th class="p-8">No. Invoice</th>
                    <th class="p-8">Tanggal</th>
                    <th class="p-8">Pembeli / Product</th>
                    <th class="p-8 text-center">Qty</th>
                    <th class="p-8">Total</th>
                    <th class="p-8">Status</th>
                    <th class="p-8 text-center">Update Status</th>
                </tr>
            </thead>
            <tbody class="text-xs font-bold divide-y divide-gray-50">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-50/30 transition text-black">
                    <td class="p-8 text-black italic font-black">
                        <a href="{{ route('admin.orders.detail', $order->id) }}" class="underline hover:text-blue-600">
                            #{{ $order->invoice }}
                        </a>
                    </td>
                    
                    <td class="p-8 text-gray-400 font-medium">{{ $order->created_at->format('d M Y') }}</td>
                    
                    <td class="p-8">
                        <p class="uppercase font-black italic text-black">{{ $order->user->name }}</p>
                        <p class="text-gray-400 text-[10px] font-bold normal-case mt-0.5">
                            {{ $order->product->nama_produk ?? 'Sneakers Koleksi' }} (Size: {{ $order->size ?? 'N/A' }})
                        </p>
                    </td>
                    
                    <td class="p-8 text-center text-black font-black text-sm">{{ $order->quantity ?? 1 }} Pcs</td>
                    
                    <td class="p-8 font-black italic text-blue-600 text-sm">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    
                    <td class="p-8">
                        @php
                            $statusColor = [
                                'Pending' => 'bg-gray-50 text-gray-500 border-gray-200',
                                'Diproses' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                'Dikirim' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'Dibatalkan' => 'bg-red-50 text-red-600 border-red-100',
                                'Selesai' => 'bg-green-50 text-green-600 border-green-100',
                            ];
                        @endphp
                        <span class="{{ $statusColor[$order->status] ?? 'bg-gray-50 text-gray-500 border-gray-100' }} px-4 py-1.5 rounded-full text-[10px] border font-black uppercase italic tracking-wider">
                            {{ $order->status }}
                        </span>
                    </td>
                    
                    <td class="p-8">
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="flex gap-2 items-center justify-center">
                            @csrf
                            <select name="status" class="bg-[#f9f9f9] border-none rounded-xl p-2.5 text-[10px] outline-none font-black uppercase italic cursor-pointer text-black">
                                <option value="Diproses" {{ $order->status == 'Diproses' ? 'selected' : '' }}>Diproses (Packing)</option>
                                <option value="Dikirim" {{ $order->status == 'Dikirim' ? 'selected' : '' }}>Dikirim (Kurir)</option>
                                <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai (Diterima)</option>
                                <option value="Dibatalkan" {{ $order->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            <button type="submit" class="bg-black text-white p-2.5 rounded-xl hover:bg-gray-800 transition flex items-center justify-center shadow-md">
                                <i class="fas fa-save text-[10px]"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-20 text-center text-gray-400 font-bold uppercase tracking-widest bg-gray-50/20">
                        Belum ada records log riwayat transaksi terkumpul.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection