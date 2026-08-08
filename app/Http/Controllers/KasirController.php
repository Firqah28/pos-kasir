<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                $totalHarga += ($item['harga_jual'] * $item['qty']);
            }

            $transaksiId = DB::table('transaksi')->insertGetId([
                'user_id' => $userId,
                'store_id' => $storeId,
                'total_harga' => $totalHarga,
                'bayar' => $request->bayar,
                'kembalian' => $request->kembalian,
                'metode_bayar' => $request->metode_bayar ?? 'tunai',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['harga_jual'] * $item['qty'];

                $barang = DB::table('barang')
                    ->where('id', $item['barang_id'])
                    ->when($storeId, fn ($q) => $q->where('store_id', $storeId))
                    ->first();
                $hargaModal = $barang ? $barang->harga_beli : 0;

                DB::table('detail_transaksi')->insert([
                    'transaksi_id' => $transaksiId,
                    'store_id' => $storeId,
                    'barang_id' => $item['barang_id'],
                    'harga_jual' => $item['harga_jual'],
                    'harga_modal' => $hargaModal,
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal,
                ]);

                // Decrease stock
                DB::table('barang')
                    ->where('id', $item['barang_id'])
                    ->when($storeId, fn ($q) => $q->where('store_id', $storeId))
                    ->decrement('stok', $item['qty']);
            }

            DB::commit();

            return response()->json(['success' => true, 'transaksi_id' => $transaksiId]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Gagal memproses transaksi: '.$e->getMessage()], 400);
        }
    }

    public function cetakStruk($id)
    {
        $storeId = $this->currentStoreId();

        $transaksi = DB::table('transaksi')
            ->leftJoin('users', 'transaksi.user_id', '=', 'users.id')
            ->select('transaksi.*', 'users.username as kasir')
            ->where('transaksi.id', $id)
            ->when($storeId, fn ($q) => $q->where('transaksi.store_id', $storeId))
            ->first();

        if (! $transaksi) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        $detail = DB::table('detail_transaksi')
            ->join('barang', 'detail_transaksi.barang_id', '=', 'barang.id')
            ->select('detail_transaksi.*', 'barang.nama_barang')
            ->where('transaksi_id', $id)
            ->when($storeId, fn ($q) => $q->where('detail_transaksi.store_id', $storeId))
            ->get();

        return view('struk', compact('transaksi', 'detail'));
    }
}
