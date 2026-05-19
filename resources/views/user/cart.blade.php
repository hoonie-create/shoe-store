@extends('layouts.user')

@section('content')
<div class="px-8 pb-20 bg-white min-h-screen">   
    <div class="border-b pb-4 mb-10 mt-4">
        <h2 class="text-2xl font-black uppercase italic tracking-tighter">Keranjang Belanja</h2>
    </div>

    @if($cartItems->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <h3 class="text-xl font-bold mb-2">Wah, keranjangmu masih kosong!</h3>
            <a href="{{ route('home') }}" class="bg-black text-white px-10 py-3 rounded-full font-bold hover:bg-gray-800 transition">MULAI BELANJA</a>
        </div>
    @else
        <form action="{{ route('checkout.direct') }}" method="GET" id="form-checkout">
            <div class="max-w-4xl mx-auto">
                
                <div class="flex items-center gap-3 bg-gray-50 p-4 rounded-2xl mb-6 border border-gray-100">
                    <input type="checkbox" id="select-all" class="w-5 h-5 rounded border-gray-300 accent-black cursor-pointer">
                    <label for="select-all" class="text-xs font-black uppercase tracking-widest text-black cursor-pointer select-none">
                        Pilih Semua Produk ({{ $cartItems->count() }})
                    </label>
                </div>

                <div class="space-y-6 mb-12">
                    @foreach($cartItems as $item)
                    <div class="flex items-center gap-6 border-b pb-6 cart-item-row" 
                         data-price="{{ $item->product->harga }}" 
                         data-qty="{{ $item->quantity }}" 
                         data-id="{{ $item->id }}">
                        
                        <div class="flex items-center justify-center">
                            <input type="checkbox" name="cart_ids[]" value="{{ $item->id }}" 
                                   class="item-checkbox w-5 h-5 rounded border-gray-300 accent-black cursor-pointer">
                        </div>

                        <div class="w-32 h-32 bg-[#f9f9f9] rounded-2xl flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('storage/' . $item->product->foto) }}" class="w-3/4 object-contain">
                        </div>

                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-lg uppercase italic text-black">{{ $item->product->nama_produk }}</h3>
                                    <p class="text-gray-400 text-sm italic tracking-widest">SIZE: {{ $item->size }} EU</p>
                                </div>
                                <p class="font-black text-lg text-black">Rp {{ number_format($item->product->harga, 0, ',', '.') }}</p>
                            </div>

                            <div class="flex items-center gap-6 mt-4">
                                <div class="flex items-center border-2 rounded-xl overflow-hidden bg-gray-50">
                                    <button type="button" onclick="updateQty({{ $item->id }}, -1)" class="px-3 py-1 hover:bg-black hover:text-white transition font-bold">-</button>
                                    <span id="qty-{{ $item->id }}" class="px-4 font-bold text-sm text-black">{{ $item->quantity }}</span>
                                    <button type="button" onclick="updateQty({{ $item->id }}, 1)" class="px-3 py-1 hover:bg-black hover:text-white transition font-bold">+</button>
                                </div>

                                <button type="button" onclick="deleteItem({{ $item->id }})" class="text-red-500 text-xs font-black uppercase tracking-tighter flex items-center gap-1 hover:underline bg-transparent border-none cursor-pointer">
                                    <i class="fas fa-trash"></i> HAPUS
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="bg-[#f9f9f9] rounded-3xl p-8 shadow-sm">
                    <h3 class="font-black uppercase italic mb-6 border-b pb-4 text-black">Ringkasan Pesanan</h3>
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span class="font-bold uppercase italic">Subtotal</span>
                            <span class="font-black text-black" id="summary-subtotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span class="font-bold uppercase italic">Pengiriman (Flat)</span>
                            <span class="font-black text-black" id="summary-shipping">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500 border-b pb-4">
                            <span class="font-bold uppercase italic">PPN (11%)</span>
                            <span class="font-black text-black" id="summary-tax">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-sm font-black uppercase italic tracking-tighter text-black">Total Tagihan</span>
                            <span class="text-3xl font-black italic text-black" id="summary-total">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" id="btn-checkout" disabled 
                            class="block w-full bg-gray-300 text-gray-500 text-center py-4 rounded-xl font-black tracking-[0.2em] transition shadow-xl uppercase italic cursor-not-allowed">
                        Pilih Produk Terlebih Dahulu
                    </button>
                </div>

            </div>
        </form>

        <form id="form-delete-hidden" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const btnCheckout = document.getElementById('btn-checkout');

    // Fungsi menghitung harga total berdasarkan item yang dicentang
    function calculateLiveTotal() {
        let subtotal = 0;
        let checkedCount = 0;

        document.querySelectorAll('.cart-item-row').forEach(row => {
            const checkbox = row.querySelector('.item-checkbox');
            if (checkbox.checked) {
                const price = parseFloat(row.getAttribute('data-price'));
                const qty = parseInt(row.getAttribute('data-qty'));
                subtotal += price * qty;
                checkedCount++;
            }
        });

        // Pengkondisian Ongkir Flat Rp 25.000 jika ada produk terpilih
        const shipping = checkedCount > 0 ? 25000 : 0;
        const tax = subtotal * 0.11;
        const total = subtotal + shipping + tax;

        // Render angka ke elemen HTML Ringkasan
        document.getElementById('summary-subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('summary-shipping').innerText = 'Rp ' + shipping.toLocaleString('id-ID');
        document.getElementById('summary-tax').innerText = 'Rp ' + Math.round(tax).toLocaleString('id-ID');
        document.getElementById('summary-total').innerText = 'Rp ' + Math.round(total).toLocaleString('id-ID');

        // Validasi tombol checkout aktif / mati
        if (checkedCount > 0) {
            btnCheckout.disabled = false;
            btnCheckout.innerText = "Checkout Sekarang (" + checkedCount + ")";
            btnCheckout.className = "block w-full bg-black text-white text-center py-4 rounded-xl font-black tracking-[0.2em] hover:bg-gray-800 transition shadow-xl uppercase italic cursor-pointer";
        } else {
            btnCheckout.disabled = true;
            btnCheckout.innerText = "Pilih Produk Terlebih Dahulu";
            btnCheckout.className = "block w-full bg-gray-300 text-gray-500 text-center py-4 rounded-xl font-black tracking-[0.2em] transition shadow-xl uppercase italic cursor-not-allowed";
        }
    }

    // Aksi Klik Pilih Semua (Select All)
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            itemCheckboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
            calculateLiveTotal();
        });
    }

    // Aksi Klik Checkbox Satuan
    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            // Jika ada salah satu dicentang lepas, matikan status Select All
            const allChecked = Array.from(itemCheckboxes).every(c => c.checked);
            selectAllCheckbox.checked = allChecked;
            calculateLiveTotal();
        });
    });

    // Inisialisasi hitungan awal (Rp 0 saat halaman baru dimuat)
    calculateLiveTotal();
});

// Fungsi merubah kuantitas item via AJAX
function updateQty(id, delta) {
    const qtySpan = document.getElementById('qty-' + id);
    let newQty = parseInt(qtySpan.innerText) + delta;

    if (newQty < 1) return;

    fetch("{{ route('cart.update') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ id: id, quantity: newQty })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload(); 
        }
    })
    .catch(err => console.error(err));
}

// Fungsi menghapus item dari keranjang
function deleteItem(id) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
        const form = document.getElementById('form-delete-hidden');
        form.action = "/cart/remove/" + id; // Sesuaikan rute URL destinasinya
        form.submit();
    }
}
</script>
@endsection