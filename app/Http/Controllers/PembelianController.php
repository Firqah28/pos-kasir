<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
        return view('pembelian');
    }

    public function apiStorePembelian(Request $request)
    {
        /*
          Expected payload:
          {
            supplier_id: 1,
            items: [
                { barang_id: 1, qty: 10, harga_beli: 40000 }
            ]
          }
        */

        if ($this->storeIsInactive()) {
            return response()->json(['error' => $this->storeBlockMessage()], 403);
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $userId = $user ? $user->id : null;
            $storeId = $this->currentStoreId();

            $totalHarga = 0;
            foreach ($request->items as $item) {
                $totalHarga += ($item['harga_beli'] * $item['qty']);
            }

            $pembelianId = DB::table('pembelian')->insertGetId([
                'user_id' => $userId,
                'store_id' => $storeId,
                'supplier_id' => $request->supplier_id,
                'total_harga' => $totalHarga,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['harga_beli'] * $item['qty'];

                DB::table('detail_pembelian')->insert([
                    'pembelian_id' => $pembelianId,
                    'store_id' => $storeId,
                    'barang_id' => $item['barang_id'],
                    'harga_beli' => $item['harga_beli'],
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal,
                ]);

                // Increase stock and update harga_beli in barang
                DB::table('barang')
                    ->where('id', $item['barang_id'])
                    ->when($storeId, fn ($q) => $q->where('store_id', $storeId))
                    ->update([
                        'harga_beli' => $item['harga_beli'],
                        'stok' => DB::raw("stok + {$item['qty']}"),
                        'updated_at' => now(),
                    ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'pembelian_id' => $pembelianId]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Gagal memproses pembelian: '.$e->getMessage()], 400);
        }
    }
}
