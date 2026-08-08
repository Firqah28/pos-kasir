<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'menunggu_pembayaran', 'nonaktif'])->default('menunggu_pembayaran')->after('fee_persen');
        });

        DB::table('stores')->get()->each(function ($store) {
            DB::table('stores')->where('id', $store->id)->update([
                'status' => $store->is_active ? 'aktif' : 'menunggu_pembayaran',
            ]);
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        DB::table('stores')->get()->each(function ($store) {
            DB::table('stores')->where('id', $store->id)->update([
                'is_active' => $store->status === 'aktif',
            ]);
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
