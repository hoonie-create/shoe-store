<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan kolom jika belum ada
            if (!Schema::hasColumn('orders', 'phone')) $table->string('phone')->nullable();
            if (!Schema::hasColumn('orders', 'address')) $table->text('address')->nullable();
            if (!Schema::hasColumn('orders', 'payment_method')) $table->string('payment_method')->nullable();
            if (!Schema::hasColumn('orders', 'payment_proof')) $table->string('payment_proof')->nullable();
            if (!Schema::hasColumn('orders', 'quantity')) $table->integer('quantity')->default(1);
            if (!Schema::hasColumn('orders', 'product_id')) $table->unsignedBigInteger('product_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
