@extends('layouts.user')

@section('content')
<div class="container mx-auto px-8 py-10">
    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-600 rounded-2xl font-bold text-xs uppercase tracking-widest border border-green-100">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Breadcrumb / Back Button --}}
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-black transition mb-8 font-bold text-xs tracking-widest group">
        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> BACK TO EXPLORE
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
        {{-- Gambar Produk --}}
        <div class="bg-[#f9f9f9] rounded-[3rem] p-12 flex items-center justify-center shadow-inner relative group">
            <img src="{{ asset('storage/' . $product->foto) }}" 
                 class="w-full h-auto drop-shadow-2xl group-hover:scale-105 transition duration-700">
            
            <div class="absolute top-8 right-8 bg-white/80 backdrop-blur-md px-4 py-2 rounded-full shadow-sm border border-gray-100">
                <p class="text-[10px] font-black uppercase tracking-widest {{ $product->stok < 5 ? 'text-red-500' : 'text-gray-500' }}">
                    Sisa Stok: {{ $product->stok }} Pcs
                </p>
            </div>
        </div>

        {{-- Info Produk --}}
        <div class="flex flex-col justify-center">
            <div class="mb-8">
                <span class="text-gray-400 text-xs font-black tracking-[0.3em] uppercase italic">Premium Sneakers</span>
                <h1 class="text-5xl font-black italic uppercase tracking-tighter mt-2 leading-none">{{ $product->nama_produk }}</h1>
                <p class="text-blue-600 text-3xl font-black italic mt-4">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
            </div>

            <p class="text-gray-500 text-sm leading-relaxed mb-10 max-w-lg">
                {{ $product->deskripsi }}
            </p>

            {{-- Form Add to Cart & Buy Now --}}
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                <div class="mb-8">
                    <h3 class="text-xs font-black tracking-widest uppercase mb-4">Select Size</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach(explode(',', $product->ukuran) as $sz)
                        <label class="cursor-pointer">
                            <input type="radio" name="size" value="{{ trim($sz) }}" class="hidden peer" required>
                            <span class="w-14 h-14 flex items-center justify-center border-2 border-gray-100 rounded-2xl font-bold text-sm peer-checked:border-black peer-checked:bg-black peer-checked:text-white transition-all uppercase hover:border-gray-300">
                                {{ trim($sz) }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-10 space-y-3">
                    <h3 class="text-xs font-black tracking-widest uppercase">Quantity</h3>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stok }}" 
                        class="w-24 bg-gray-50 border-none rounded-xl py-3 px-4 font-black text-center outline-none focus:ring-2 focus:ring-black">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button type="submit" name="action" value="cart" 
                            class="border-2 border-black py-5 rounded-2xl font-black text-[10px] tracking-[0.2em] uppercase hover:bg-black hover:text-white transition-all duration-300 shadow-sm">
                        Add to Cart
                    </button>
                    
                    <button type="submit" name="action" value="buy_now" 
                            class="bg-black text-white py-5 rounded-2xl font-black text-[10px] tracking-[0.2em] uppercase text-center hover:bg-gray-800 transition-all duration-300 shadow-xl">
                        Buy Now
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- FITUR: CUSTOMER REVIEWS (DINAMIS DENGAN INTEGRASI RATING) --}}
    <div class="mt-24 border-t pt-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            
            {{-- Bagian Kiri: Skor Rata-rata dan Total Ulasan --}}
            <div>
                <h2 class="text-2xl font-black uppercase italic tracking-tighter mb-4">Customer Reviews</h2>
                <p class="text-gray-400 text-xs leading-relaxed mb-6">Berikan pendapatmu tentang sneakers ini untuk membantu pembeli lain.</p>
                
                {{-- Box Komponen Ringkasan Statistik Rating --}}
                <div class="flex items-center gap-6 bg-[#f9f9f9] p-6 rounded-[2rem] border border-gray-100 shadow-inner">
                    <div class="text-center">
                        <h3 class="text-4xl font-black tracking-tight text-black italic">{{ number_format($product->averageRating(), 1) }}</h3>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">out of 5</p>
                    </div>
                    <div class="flex flex-col justify-center">
                        <div class="text-yellow-400 text-xs flex gap-0.5 mb-1">
                            @php $avgStars = round($product->averageRating()); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $avgStars ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">({{ $product->totalReviews() }} Reviews)</p>
                    </div>
                </div>
            </div>
            
            {{-- Bagian Kanan: Form Input Review & List Ulasan --}}
            <div class="lg:col-span-2">
                {{-- Form Input Review --}}
                <div class="bg-[#f9f9f9] p-8 rounded-[2rem] mb-10 shadow-inner border border-gray-50">
                    <form action="{{ route('review.store', $product->id) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        {{-- Input Bintang Interaktif Terbalik (Aman & Clean CSS Effect) --}}
                        <div class="flex items-center gap-3 bg-white px-5 py-3 rounded-xl w-fit border border-gray-100 shadow-sm">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Rating Anda:</span>
                            <div class="flex flex-row-reverse gap-1 rating-stars">
                                <input type="radio" id="star5" name="rating" value="5" class="hidden peer" required />
                                <label for="star5" class="cursor-pointer text-gray-200 hover:text-yellow-400 peer-checked:text-yellow-400 peer-hover:text-yellow-400 fas fa-star text-sm transition-colors"></label>
                                
                                <input type="radio" id="star4" name="rating" value="4" class="hidden peer" />
                                <label for="star4" class="cursor-pointer text-gray-200 hover:text-yellow-400 peer-checked:text-yellow-400 peer-hover:text-yellow-400 fas fa-star text-sm transition-colors"></label>
                                
                                <input type="radio" id="star3" name="rating" value="3" class="hidden peer" />
                                <label for="star3" class="cursor-pointer text-gray-200 hover:text-yellow-400 peer-checked:text-yellow-400 peer-hover:text-yellow-400 fas fa-star text-sm transition-colors"></label>
                                
                                <input type="radio" id="star2" name="rating" value="2" class="hidden peer" />
                                <label for="star2" class="cursor-pointer text-gray-200 hover:text-yellow-400 peer-checked:text-yellow-400 peer-hover:text-yellow-400 fas fa-star text-sm transition-colors"></label>
                                
                                <input type="radio" id="star1" name="rating" value="1" class="hidden peer" />
                                <label for="star1" class="cursor-pointer text-gray-200 hover:text-yellow-400 peer-checked:text-yellow-400 peer-hover:text-yellow-400 fas fa-star text-sm transition-colors"></label>
                            </div>
                        </div>

                        <textarea name="comment" required minlength="5"
                                  class="w-full bg-white border-none rounded-2xl p-6 text-sm outline-none focus:ring-2 focus:ring-black transition-all shadow-sm" 
                                  rows="3" placeholder="Tulis komentar kamu di sini..."></textarea>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="bg-black text-white px-10 py-3 rounded-xl font-black text-[10px] tracking-widest uppercase hover:bg-gray-800 transition shadow-md">
                                Simpan Komentar
                            </button>
                        </div>
                    </form>
                </div>

                {{-- List Review dari Database --}}
                <div class="space-y-6">
                    @forelse($product->reviews as $review)
                    <div class="border-b border-gray-100 pb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-black text-sm uppercase italic text-black">{{ $review->user->name }}</span>
                            <div class="flex items-center gap-2">
                                <div class="text-yellow-400 text-[10px] flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </div>
                                <span class="ml-2 text-gray-300 font-bold text-[10px] normal-case">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <p class="text-gray-500 text-xs italic font-medium leading-relaxed">"{{ $review->comment }}"</p>
                    </div>
                    @empty
                    <div class="text-center py-12 bg-[#f9f9f9] border border-dashed border-gray-200 rounded-3xl">
                        <p class="text-gray-400 text-xs italic font-medium">Belum ada ulasan untuk produk ini. Jadi yang pertama memberikan review!</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

{{-- CSS Trick untuk Hover Bintang Searah --}}
<style>
    .rating-stars label:hover ~,
    .rating-stars input:checked ~ label {
        color: #facc15 !important;
    }
</style>
@endsection