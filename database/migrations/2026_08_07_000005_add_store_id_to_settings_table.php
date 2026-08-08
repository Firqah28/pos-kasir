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
        Schema::table('settings', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->after('id');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique('settings_key_unique');
        });

        Schema::table('settings', function (Blueprint $table) {
            // Setting hanya berlaku untuk satu cabang; store_id NULL = default global.
            $table->unique(['store_id', 'key'], 'settings_store_key_unique');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropUnique('settings_store_key_unique');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->unique('key', 'settings_key_unique');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('store_id');
        });
    }
};
