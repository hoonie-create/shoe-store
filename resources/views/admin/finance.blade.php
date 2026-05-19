@extends('layouts.admin')

@section('content')
<div class="px-8 pb-20 bg-white min-h-screen">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-10 mt-4 print:mb-5">
        <div>
            <h2 class="text-3xl font-black uppercase italic tracking-tighter text-black">Laporan Keuangan</h2>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-1">
                PERIODE: 
                @if(request('start_date') && request('end_date'))
                    {{ date('d M Y', strtotime(request('start_date'))) }} - {{ date('d M Y', strtotime(request('end_date'))) }}
                @else
                    {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}
                @endif
            </p>
        </div>
        <div class="flex gap-3 print:hidden">
            <button onclick="window.print()" class="bg-black text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-800 transition shadow-lg">
                <i class="fas fa-print mr-2"></i> Cetak Laporan
            </button>
        </div>
    </div>

    {{-- Filter Form (Hidden when printing) --}}
    <div class="bg-gray-50 p-8 rounded-[2rem] mb-10 print:hidden border border-gray-100">
        <form action="{{ route('admin.finance.index') }}" method="GET" class="flex flex-wrap items-end gap-6">

            {{-- TAMBAHAN FILTER: DARI TANGGAL --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                       class="block w-44 bg-white border-none rounded-xl p-2.5 text-xs font-bold focus:ring-2 focus:ring-black outline-none shadow-sm text-black">
            </div>

            {{-- TAMBAHAN FILTER: SAMPAI TANGGAL --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                       class="block w-44 bg-white border-none rounded-xl p-2.5 text-xs font-bold focus:ring-2 focus:ring-black outline-none shadow-sm text-black">
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="flex gap-2">
                <button type="submit" class="bg-black text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-800 transition">
                    Terapkan
                </button>
                @if(request('start_date') || request('end_date') || request('month') != date('m') || request('year') != date('Y'))
                    <a href="{{ route('admin.finance.index') }}" class="bg-white text-black border border-gray-200 px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Statistik Ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="border-2 border-black p-8 rounded-[2rem] bg-neutral-50/50">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Total Pendapatan Bersih</p>
            <h3 class="text-4xl font-black italic text-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-gray-50 p-8 rounded-[2rem] border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Total Transaksi Selesai</p>
            <h3 class="text-4xl font-black italic text-black">{{ $totalTransactions }} TRX</h3>
        </div>
    </div>

    {{-- Tabel Detail --}}
    <div class="bg-white border border-gray-100 rounded-[2rem] overflow-hidden shadow-sm">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-[10px] text-gray-400 uppercase tracking-widest font-black border-b border-gray-100">
                    <th class="p-6">Invoice</th>
                    <th class="p-6">Tanggal Selesai</th>
                    <th class="p-6">Nama Pelanggan</th>
                    <th class="p-6 text-right">Nominal</th>
                </tr>
            </thead>
            <tbody class="text-xs font-bold divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50/30 transition text-black">
                    <td class="p-6 italic font-black">#{{ $order->invoice }}</td>
                    <td class="p-6 text-gray-500 font-medium">{{ $order->updated_at->format('d M Y, H:i') }} WIB</td>
                    <td class="p-6 uppercase">{{ $order->user->name ?? 'User Terhapus' }}</td>
                    <td class="p-6 text-right font-black italic text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-20 text-center text-gray-300 uppercase tracking-widest font-black bg-gray-50/10">Tidak ada data transaksi pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
            @if(!$orders->isEmpty())
            <tfoot class="bg-black text-white">
                <tr>
                    <td colspan="3" class="p-6 font-black uppercase text-right tracking-widest">Grand Total</td>
                    <td class="p-6 text-right font-black italic text-lg">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
            </footer>
            @endif
        </table>
    </div>

    {{-- Footer Cetakan (Hanya muncul saat di-print) --}}
    <div class="hidden print:block mt-20">
        <div class="flex justify-between items-end text-center">
            <div>
                <p class="text-xs font-bold uppercase mb-20">Dicetak Oleh Admin</p>
                <div class="border-b border-black w-40 mx-auto"></div>
                <p class="text-[10px] font-black mt-2 uppercase italic">{{ auth()->user()->name }}</p>
            </div>
            <div class="text-right italic">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest">LUXESTEP Official Report</p>
                <p class="text-[10px] text-gray-400">{{ date('d/m/Y H:i') }} WIB</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Sembunyikan elemen UI */
    nav, aside, .print\:hidden, button, form { display: none !important; }
    
    /* Reset layout untuk cetakan */
    body { background: white !important; margin: 0; padding: 0; }
    .px-8 { padding-left: 0 !important; padding-right: 0 !important; }
    
    /* Paksa border muncul di PDF */
    .border, .border-2 { border-color: #000 !important; }
    .bg-gray-50 { background-color: #f9f9f9 !important; -webkit-print-color-adjust: exact; }
    .bg-black { background-color: #000 !important; color: #fff !important; -webkit-print-color-adjust: exact; }
}
</style>
@endsection