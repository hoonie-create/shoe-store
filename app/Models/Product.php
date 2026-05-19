<?php

namespace App\Http\Controllers; // Abaikan namespace ini jika bawaanmu App\Models

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['nama_produk', 'harga', 'stok', 'ukuran', 'foto', 'deskripsi'];

    /**
     * 
     */
    public function reviews() 
    {
        return $this->hasMany(Review::class)->latest();
    }

    /**
     * FITUR BARU: Menghitung rata-rata rating bintang produk
     * Menggunakan nilai decimal/float (Contoh hasil: 4.5 atau 0.0 jika belum ada review)
     */
    public function averageRating()
    {
        return round($this->reviews()->avg('rating'), 1) ?: 0.0;
    }

    /**
     * FITUR BARU: Menghitung total jumlah komentar ulasan yang masuk
     */
    public function totalReviews()
    {
        return $this->reviews()->count();
    }
}