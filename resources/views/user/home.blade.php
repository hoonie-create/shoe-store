@extends('layouts.user')

@section('content')
<div class="px-8 pb-10 bg-white">
    <div class="bg-[#f2f2f2] rounded-3xl p-10 flex items-center mb-10 shadow-sm">
        <div class="w-1/2">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/20/Adidas_Logo.svg" alt="Brand" class="h-10 mb-4 opacity-50">
            <span class="text-sm tracking-widest text-gray-500 uppercase">Men's Running</span>
            <h2 class="text-5xl font-black mt-2 mb-4 italic">ULTRA BOOST</h2>
            <p class="text-gray-600 mb-6 max-w-md">Lightweight and responsive, providing elite comfort for every step.</p>
            <p class="text-2xl font-bold mb-6">Rp3.999.000</p>
            <button class="bg-black text-white px-8 py-3 rounded-full font-bold hover:scale-105 transition">EXPLORE NOW</button>
        </div>
        <div class="w-1/2 flex justify-center items-center">
            <img src="{{ asset('gambar/banner.jpg') }}" alt="Sneaker" class="w-2/3 drop-shadow-2xl">
        </div>
    </div>

    <section class="mb-10">
        <div class="flex justify-between items-end mb-8 border-b pb-4">
           <h3 class="text-2xl font-black italic uppercase">Shop By Category</h3>
        </div>
        <div class="flex justify-between gap-4">
            
           <div class="flex flex-col items-center gap-3 group cursor-pointer">
                <div class="w-40 h-40 rounded-full border-2 overflow-hidden transition-all duration-300 group-hover:scale-110 shadow-sm">
                    <img src="{{ asset('gambar/basketball.png') }}" class="w-full h-full object-cover">
                </div>
                <span class="text-sm font-bold text-black uppercase tracking-tight">BASKETBALL</span>
            </div>

            <div class="flex flex-col items-center gap-3 group cursor-pointer">
                <div class="w-40 h-40 rounded-full border-2 overflow-hidden transition-all duration-300 group-hover:scale-110 shadow-sm">
                    <img src="{{ asset('gambar/airjordan.png') }}" class="w-full h-full object-contain">
                </div>
                <span class="text-sm font-bold text-black uppercase tracking-tight">AIR JORDAN</span>
            </div>

            <div class="flex flex-col items-center gap-3 group cursor-pointer">
                <div class="w-40 h-40 rounded-full border-2 overflow-hidden transition-all duration-300 group-hover:scale-110 shadow-sm">
                    <img src="{{ asset('gambar/football.png') }}" class="w-full h-full object-contain">
                </div>
                <span class="text-sm font-bold text-black uppercase tracking-tight">FOOTBALL</span>
            </div>

            <div class="flex flex-col items-center gap-3 group cursor-pointer">
                <div class="w-40 h-40 rounded-full border-2 overflow-hidden transition-all duration-300 group-hover:scale-110 shadow-sm">
                    <img src="{{ asset('gambar/golf.png') }}" class="w-full h-full object-contain">
                </div>
                <span class="text-sm font-bold text-black uppercase tracking-tight">GOLF</span>
            </div>

            <div class="flex flex-col items-center gap-3 group cursor-pointer">
                <div class="w-40 h-40 rounded-full border-2 overflow-hidden transition-all duration-300 group-hover:scale-110 shadow-sm">
                    <img src="{{ asset('gambar/badminton.png') }}" class="w-full h-full object-contain">
                </div>
                <span class="text-sm font-bold text-black uppercase tracking-tight">BADMINTON</span>
            </div>

        </div>
    </section>

    <section>
        <div class="flex justify-between items-end mb-8 border-b pb-4">
            <h3 class="text-2xl font-black italic uppercase">Our Products</h3>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($products as $product)
            <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group">
                <div class="relative aspect-square bg-[#f9f9f9] rounded-2xl overflow-hidden mb-4">
                    <img src="{{ asset('storage/' . $product->foto) }}" 
                        alt="{{ $product->nama_produk }}" 
                        class="w-full h-full object-contain p-4 group-hover:scale-110 transition-transform duration-500">
                </div>

                <div class="text-center space-y-2">
                    <h3 class="text-sm font-black text-black leading-tight h-10 flex items-center justify-center px-2">
                        {{ $product->nama_produk }}
                    </h3>
                    
                    {{-- FIX: RATING BINTANG DINAMIS (Menyesuaikan Rata-rata dari Tabel Reviews) --}}
                    <div class="flex items-center justify-center gap-1">
                        @php 
                            $avgRating = $product->averageRating();
                            $roundedRating = round($avgRating); 
                        @endphp
                        
                        {{-- Render Bintang Terisi vs Bintang Kosong --}}
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $roundedRating ? 'fas' : 'far' }} fa-star text-yellow-400 text-[10px]"></i>
                        @endfor
                        
                        {{-- Label Angka Desimal Rataan Rating --}}
                        <span class="text-[10px] text-gray-400 font-bold ml-1">({{ number_format($avgRating, 1) }})</span>
                    </div>

                    <p class="text-blue-600 font-black text-sm italic">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </p>

                    <div class="pt-2">
                        <a href="{{ route('product.detail', $product->id) }}" 
                        class="block w-full bg-black text-white py-3 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-gray-800 transition shadow-lg">
                            View Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</div>
@endsection