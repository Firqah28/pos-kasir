<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropUnique('barang_barcode_unique');
        });

        Schema::table('barang', function (Blueprint $table) {
            // Barcode hanya wajib unik dalam satu cabang toko yang sama.
            $table->unique(['store_id', 'barcode'], 'barang_store_barcode_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropUnique('barang_store_barcode_unique');
        });

        Schema::table('barang', function (Blueprint $table) {
            $table->unique('barcode', 'barang_barcode_unique');
        });
    }
};
