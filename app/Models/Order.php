<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Mempertahankan struktur asli milikmu agar fleksibel membaca kolom-kolom baru (size, phone, dll)
    protected $guarded = [];

    /**
     * Relasi ke model User
     * Menghubungkan pembeli invoice dengan biodata akun pendaftar
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model Product
     * Menghubungkan item pesanan dengan nama produk sneakers dan foto di gudang admin
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}