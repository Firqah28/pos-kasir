<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $store = Store::firstOrCreate(
            ['kode_toko' => 'PSR'],
            [
                'nama_toko' => 'Toko Pusat',
                'alamat' => 'Jl. Contoh No. 1',
                'telepon' => '081234567890',
                'status' => Store::STATUS_AKTIF,
            ]
        );

        User::updateOrCreate(
            ['username' => 'master'],
            [
                'password' => Hash::make('master123'),
                'role' => User::ROLE_MASTER_ADMIN,
                'store_id' => null,
            ]
        );

        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('admin123'),
                'role' => User::ROLE_ADMIN,
                'store_id' => $store->id,
            ]
        );

        User::updateOrCreate(
            ['username' => 'kasir'],
            [
                'password' => Hash::make('kasir123'),
                'role' => User::ROLE_KASIR,
                'store_id' => $store->id,
            ]
        );
    }
}
