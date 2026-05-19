<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    // Agar data bisa disimpan dari kodingan
    protected $fillable = ['user_id', 'product_id', 'quantity', 'size'];

    // Menghubungkan Cart ke Product agar bisa ambil Nama & Harga Sepatu
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}