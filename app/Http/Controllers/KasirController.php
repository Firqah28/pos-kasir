<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        return view('kasir');
    }

    public function apiStoreTransaction(Request $request)
    {
        /*
          Expected payload:
          {
            bayar: 100000,
            kembalian: 10000,
            items: [
                { barang_id: 1, qty: 2, harga: 45000 }
            ]
          }
        */
        
        DB::beginTransaction();
        try {
            $user = DB::table('users')->first();
            if (!$user) {
                $userId = DB::table('users')->insertGetId([
                    'username' => 'admin',
                    'password' => bcrypt('password'),
                    'role' => 'admin',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $userId = $user->id;
            }

            $totalHarga = 0;
            foreach ($request->items as $item) {
                $totalHarga += ($item['harga_jual'] * $item['qty']);
            }

            $transaksiId = DB::table('transaksi')->insertGetId([
                'user_id' => $userId,
                'total_harga' => $totalHarga,
                'bayar' => $request->bayar,
                'kembalian' => $request->kembalian,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['harga_jual'] * $item['qty'];

                DB::table('detail_transaksi')->insert([
                    'transaksi_id' => $transaksiId,
                    'barang_id' => $item['barang_id'],
                    'harga_jual' => $item['harga_jual'],
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal
                ]);

                // Decrease stock
                DB::table('barang')->where('id', $item['barang_id'])->decrement('stok', $item['qty']);
            }

            DB::commit();
            return response()->json(['success' => true, 'transaksi_id' => $transaksiId]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal memproses transaksi: ' . $e->getMessage()], 400);
        }
    }
}
