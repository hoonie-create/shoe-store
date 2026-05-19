<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSizeToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan kolom 'size' bertipe string setelah kolom 'quantity'
            if (!Schema::hasColumn('orders', 'size')) {
                $table->string('size')->nullable()->after('quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menghapus kembali kolom 'size' jika melakukan rollback
            $table->dropColumn('size');
        });
    }
}