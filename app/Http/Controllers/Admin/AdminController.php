<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class AdminController extends Controller
{
    /**
     * 1. DASHBOARD UTAMA ADMIN
     */
    public function index(Request $request)
    {
        // Statistik Ringkasan Atas Cards
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'user')->count();
        $ordersProcessed = Order::where('status', 'Diproses')->count();
        $totalStock = Product::sum('stok'); 
        $totalRevenue = Order::where('status', 'Selesai')->sum('total_price');

        // Mengonstruksi Query Utama dengan Eager Loading
        $query = Order::with(['user', 'product']);

        // Logika Penyaringan (Filtering) Berdasarkan Input Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Logika Pengurutan (Sorting)
        if ($request->sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // ── TABEL ATAS: DAFTAR BUKTI PEMBAYARAN (PERLU VERIFIKASI) ──
        $proofOrders = Order::with(['user', 'product'])
                            ->where('status', 'Pending')
                            ->whereNotNull('payment_proof')
                            ->latest()
                            ->get();

        // ── TABEL BAWAH: DAFTAR PESANAN MASUK (OPERASIONAL LOGISTIK) ──
        $recentOrders = (clone $query)->whereIn('status', ['Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'])->get();

        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalStock',
            'totalOrders', 
            'ordersProcessed',
            'totalUsers', 
            'totalRevenue', 
            'recentOrders',
            'proofOrders'
        ));
    }

    /**
     * 2. OTORISASI VERIFIKASI BUKTI PEMBAYARAN KONSUMEN
     */
    public function verifyPayment($id, $action)
    {
        try {
            $order = Order::findOrFail($id);

            if ($action === 'approve') {
                $order->update(['status' => 'Diproses']); 
                return redirect()->back()->with('success', "Pesanan #{$order->invoice} berhasil disetujui! Pembayaran sah dan dipindahkan ke Daftar Pesanan Masuk.");
            } elseif ($action === 'reject') {
                $order->update([
                    'status' => 'Pending',
                    'payment_proof' => null 
                ]);
                return redirect()->back()->with('error', "Pembayaran untuk pesanan #{$order->invoice} ditolak. Status dikembalikan ke Pending agar pembeli dapat mengunggah ulang.");
            }

            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * 3. LIHAT DETAIL FORM & LAYOUT SHIPPING LABEL
     */
    public function showDetail($id)
    {
        $order = Order::with(['user', 'product'])->findOrFail($id);
        return view('admin.order-detail', compact('order'));
    }

    /**
     * 4. UPDATE STATUS PROSES PESANAN (DROPDOWN OPERASIONAL TABEL BAWAH)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diproses,Dikirim,Dibatalkan,Selesai'
        ]);

        try {
            $order = Order::findOrFail($id);
            $oldStatus = $order->status;
            
            $order->status = $request->status;
            $order->save();

            return redirect()->back()->with('success', "Pesanan #{$order->invoice} berhasil diperbarui: {$oldStatus} -> {$order->status}");
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * 5. LAPORAN KEUANGAN BULANAN & RENTANG TANGGAL CUSTOM
     * FIX SINKRONISASI: Mendukung parameter filter dinamis start_date dan end_date
     */
    public function financeReport(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $query = Order::with('user')->where('status', 'Selesai');

        // FITUR UTAMA: Jika admin mengisi input rentang kalender tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('created_at', '>=', $request->start_date)
                  ->whereDate('created_at', '<=', $request->end_date);
        } else {
            // Jalur alternatif jika menggunakan filter dropdown bulan dan tahun biasa
            if ($request->filled('month')) {
                $query->whereMonth('created_at', $month);
            }
            $query->whereYear('created_at', $year);
        }

        $orders = $query->latest()->get();
        $totalRevenue = $orders->sum('total_price');
        $totalTransactions = $orders->count();

        return view('admin.finance', [
            'orders' => $orders,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'month' => $month,
            'year' => $year
        ]);
    }

    /**
     * 6. MANAJEMEN DATA PESANAN TOKO
     */
    public function manageOrders(Request $request)
    {
        $query = Order::with(['user', 'product'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();
        
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * 7. HALAMAN PENGATURAN AKUN ADMIN
     */
    public function accountSetting()
    {
        $user = auth()->user();
        return view('admin.account', compact('user'));
    }

    /**
     * 8. UPDATE DATA PROFIL UTAMA ADMIN
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Profil Administrator LuxeStep berhasil diperbarui!');
    }

    /**
     * 9. UPDATE PASSWORD ROOT ADMIN
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Password lama Administrator tidak sesuai.');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Password Administrator berhasil diubah!');
    }
}