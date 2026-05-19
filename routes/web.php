<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| LUXESTEP COMPLETE ROUTING SYSTEM
|--------------------------------------------------------------------------
*/

// --- 1. GUEST: Akses Sebelum Login ---
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () { return redirect()->route('login'); });
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister']);
});

// --- 2. AUTH: Akses Setelah Login ---
Route::middleware(['auth'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // -- USER AREA: Produk & Review Bintang Dinamis --
    Route::get('/home', [ProductController::class, 'index'])->name('home');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
    Route::post('/product/{id}/review', [ReviewController::class, 'store'])->name('review.store');

    // -- USER AREA: Keranjang (Cart) --
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // -- USER AREA: Checkout & Alur Pembayaran Baru --
    Route::get('/checkout/{id?}', [CartController::class, 'checkoutDirect'])->name('checkout.direct');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/payment/invoice/{id}', [CartController::class, 'paymentPage'])->name('payment.page');
    Route::post('/payment/upload/{id}', [CartController::class, 'uploadProof'])->name('payment.upload');
    Route::get('/receipt/{id}', [CartController::class, 'receipt'])->name('payment.success');
    Route::get('/my-orders', [CartController::class, 'myOrders'])->name('orders.index');
    
    // -- USER AREA: Profil Akun, Foto, & Password --
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::post('/account/update', [AccountController::class, 'updateProfile'])->name('account.update');
    Route::post('/account/update-foto', [AccountController::class, 'updateFoto'])->name('account.updateFoto');
    Route::post('/account/password', [AccountController::class, 'updatePassword'])->name('account.password');


    // -- ADMIN AREA (Prefix & Nama Alias Menggunakan 'admin.') --
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard Utama Admin (Memuat Statistik & Pemisahan 2 Tabel Sistem)
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        
        // REVISI OTORISASI: Jalur Verifikasi Bukti Pembayaran (Approve / Reject)
        Route::get('/payment/verify/{id}/{action}', [AdminController::class, 'verifyPayment'])->name('payment.verify');

        // FITUR BARU: Lihat Detail Informasi Checkout & Cetak Nomor Resi Cetak Fisik
        Route::get('/orders/{id}/detail', [AdminController::class, 'showDetail'])->name('orders.detail');

        // Manajemen Produk (CRUD Berkas Sepatu)
        Route::get('/products', [ProductController::class, 'indexAdmin'])->name('products.index');
        Route::resource('products', ProductController::class)->except(['index']);
        
        // Manajemen Logistik Status Pesanan Toko (Tabel Bawah Dashboard yang Sudah Ter-approve)
        Route::get('/orders', [AdminController::class, 'manageOrders'])->name('orders.index');
        Route::post('/orders/{id}/update-status', [AdminController::class, 'updateStatus'])->name('orders.updateStatus');
        
        // Laporan Keuangan Keuntungan
        Route::get('/finance', [AdminController::class, 'financeReport'])->name('finance.index');

        // Manajemen User (Melihat Akun Teregistrasi di LuxeStep)
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{id}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
        Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');

        // Pengaturan Akun Internal Admin
        Route::get('/account', [AdminController::class, 'accountSetting'])->name('account.index');
        Route::post('/account/update', [AdminController::class, 'updateProfile'])->name('account.update');
        Route::post('/account/password', [AdminController::class, 'updatePassword'])->name('account.password');
    });

});