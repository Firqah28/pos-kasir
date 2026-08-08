<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables operasional yang mengacu pada cabang (store) pemilik data.
     */
    protected array $tables = [
        'barang',
        'kategori',
        'supplier',
        'transaksi',
        'pembelian',
        'detail_transaksi',
        'detail_pembelian',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('store_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('stores')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropConstrainedForeignId('store_id');
            });
        }
    }
};
