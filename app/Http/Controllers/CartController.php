<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * RUMUS PUSAT KALKULASI LUXESTEP
     * Menjamin nominal harga konsisten di semua halaman.
     */
    private function calculateTotals($cartItems)
    {
        $subtotal = $cartItems->sum(function($item) {
            $productPrice = isset($item->product) ? $item->product->harga : ($item->harga ?? 0);
            return $productPrice * $item->quantity;
        });

        $shipping = $cartItems->isEmpty() ? 0 : 25000; // Ongkir Flat Rp 25.000
        $tax = $subtotal * 0.11; // PPN 11%
        $total = $subtotal + $shipping + $tax;

        return compact('subtotal', 'shipping', 'tax', 'total');
    }

    /**
     * 1. FITUR: ADD TO CART & BUY NOW HANDLER
     * SINKRONISASI: Mengirim pesan sukses ke session untuk memicu pop-up Toast melayang
     */
    public function addToCart(Request $request, $id)
    {
        $request->validate([
            'size' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($id);
        $userId = Auth::id();

        if ($request->action == 'buy_now') {
            return redirect()->route('checkout.direct', [
                'id' => $id,
                'quantity' => $request->quantity,
                'size' => $request->size
            ]);
        }

        $existingCart = Cart::where('user_id', $userId)
                            ->where('product_id', $id)
                            ->where('size', $request->size)
                            ->first();

        if ($existingCart) {
            $existingCart->update([
                'quantity' => $existingCart->quantity + $request->quantity
            ]);
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $id,
                'size' => $request->size,
                'quantity' => $request->quantity,
                'price' => $product->harga
            ]);
        }

        // Mengembalikan ke halaman produk dengan membawa session 'success' untuk memicu Toast Notifikasi & update Counter Sidebar
        return redirect()->back()->with('success', 'Produk premium pilihan Anda berhasil dimasukkan ke dalam keranjang!');
    }

    /**
     * 2. HALAMAN KERANJANG BELANJA
     */
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $pricing = $this->calculateTotals($cartItems);
        
        return view('user.cart', array_merge(['cartItems' => $cartItems], $pricing));
    }

    /**
     * 3. HALAMAN FORM DETAIL PESANAN (CHECKOUT INTERFACE)
     * MENERIMA SELEKSI CHECKBOX DARI KARTU KERANJANG BELANJA
     */
    public function checkoutDirect(Request $request, $id = null)
    {
        if ($id) {
            // Alur "Beli Sekarang" langsung dari Detail Produk
            $product = Product::findOrFail($id);
            $quantity = $request->input('quantity', 1);
            $size = $request->input('size', 'Default');
            
            $item = (object)[
                'product' => $product,
                'quantity' => $quantity,
                'size' => $size,
                'product_id' => $id
            ];
            $cartItems = collect([$item]);
            $product_id = $id;
        } else {
            // Alur Checkout dari halaman Keranjang Belanja
            $query = Cart::where('user_id', Auth::id())->with('product');

            // FITUR UTAMA: Memfilter item berdasarkan checkbox yang dipilih user
            if ($request->has('cart_ids')) {
                $query->whereIn('id', $request->cart_ids);
            }

            $cartItems = $query->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Silakan pilih produk terlebih dahulu untuk checkout.');
            }

            $quantity = $cartItems->sum('quantity');
            $size = $cartItems->first()->size;
            $product_id = null;
        }

        $pricing = $this->calculateTotals($cartItems);

        // Mengirimkan data array 'cart_ids' ke view checkout agar bisa dihapus pasca-pembayaran sukses
        $selectedCartIds = $request->input('cart_ids', []);

        return view('user.checkout', array_merge([
            'cartItems' => $cartItems,
            'product' => $id ? $product : ($cartItems->first()->product ?? null),
            'quantity' => $quantity,
            'size' => $size,
            'product_id' => $product_id,
            'selectedCartIds' => $selectedCartIds
        ], $pricing));
    }

    /**
     * 4. PROSES SUBMIT PESANAN UTAMA (SIMPAN FORM IDENTITAS)
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'payment_method' => 'required|in:BANK,QRIS',
        ]);

        return DB::transaction(function () use ($request) {
            $userId = Auth::id();

            if ($request->product_id) {
                // Proses instan beli langsung
                $product = Product::findOrFail($request->product_id);
                $item = (object)['product' => $product, 'quantity' => $request->quantity ?? 1, 'size' => $request->size];
                $cartItems = collect([$item]);
            } else {
                // Ambil data item keranjang belanja yang lolos seleksi checkout saja
                $query = Cart::where('user_id', $userId)->with('product');
                
                if ($request->has('selected_cart_ids')) {
                    $query->whereIn('id', $request->selected_cart_ids);
                }
                
                $cartItems = $query->get();
            }

            if ($cartItems->isEmpty()) {
                return redirect()->route('home')->with('error', 'Pesanan gagal diproses. Item kosong.');
            }

            $pricing = $this->calculateTotals($cartItems);
            $invoice = 'LXS-' . strtoupper(Str::random(8));

            // Membuat record induk pesanan
            $order = Order::create([
                'user_id'        => $userId,
                'product_id'     => $request->product_id ?? $cartItems->first()->product_id,
                'invoice'        => $invoice,
                'quantity'       => $request->quantity ?? $cartItems->sum('quantity'),
                'size'           => $request->size ?? $cartItems->first()->size,
                'subtotal'       => $pricing['subtotal'],
                'tax'            => $pricing['tax'],
                'shipping'       => $pricing['shipping'],
                'total_price'    => $pricing['total'],
                'status'         => 'Pending',
                'phone'          => $request->phone,
                'address'        => $request->address,
                'payment_method' => $request->payment_method,
            ]);

            // HAPUS SECARA SPESIFIK: Hanya menghapus item di keranjang yang dicentang saat checkout
            if ($request->product_id) {
                Cart::where('user_id', $userId)->where('product_id', $request->product_id)->delete();
            } else {
                if ($request->has('selected_cart_ids')) {
                    Cart::where('user_id', $userId)->whereIn('id', $request->selected_cart_ids)->delete();
                } else {
                    Cart::where('user_id', $userId)->delete();
                }
            }

            return redirect()->route('payment.page', $order->id);
        });
    }

    /**
     * 5. HALAMAN DETAIL INSTRUKSI PEMBAYARAN & UNGHAH BUKTI
     */
    public function paymentPage($id)
    {
        $order = Order::where('user_id', Auth::id())->with('product')->findOrFail($id);
        return view('user.payment', compact('order'));
    }

    /**
     * 6. PROSES UNGGHAH BUKTI TRANSFER (SUBMIT BUKTI)
     */
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('proofs', 'public');
            $order->update([
                'payment_proof' => $path
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu persetujuan admin.');
    }

    /**
     * 7. HALAMAN RIWAYAT PESANAN KONSUMEN (MY ORDERS)
     */
    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
                        ->with('product')
                        ->latest()
                        ->get();

        return view('user.orders', compact('orders'));
    }

    /**
     * 8. HALAMAN STRUK PEMBAYARAN KONSUMEN (RECEIPT APPROVED)
     */
    public function receipt($id)
    {
        $order = Order::where('user_id', Auth::id())->with('product')->findOrFail($id);
        return view('user.receipt', compact('order'));
    }

    /**
     * FUNGSI PENDUKUNG: PENGHAPUSAN ITEM KERANJANG
     */
    public function remove($id)
    {
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    /**
     * FUNGSI PENDUKUNG: UPDATE JUMLAH ITEM (AJAX QUANTITY)
     */
    public function updateQuantity(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())->find($request->id);
        if ($cart) {
            $cart->update(['quantity' => $request->quantity]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}