<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index()
    {
        return view('barang');
    }

    public function apiIndex()
    {
        $barang = DB::table('barang')
            ->leftJoin('kategori', 'barang.kategori_id', '=', 'kategori.id')
            ->leftJoin('supplier', 'barang.supplier_id', '=', 'supplier.id')
            ->select('barang.*', 'kategori.nama_kategori', 'supplier.nama_supplier')
            ->get();
            
        return response()->json($barang);
    }

    public function apiShow($id_or_barcode)
    {
        $barang = DB::table('barang')
            ->where('id', $id_or_barcode)
            ->orWhere('barcode', $id_or_barcode)
            ->first();
            
        if ($barang) {
            return response()->json($barang);
        }
        return response()->json(['message' => 'Not found'], 404);
    }

    public function apiStore(Request $request)
    {
        try {
            $id = DB::table('barang')->insertGetId([
                'barcode' => $request->barcode,
                'nama_barang' => $request->nama_barang,
                'kategori_id' => $request->kategori_id ?: null,
                'supplier_id' => $request->supplier_id ?: null,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'stok' => $request->stok,
                'satuan' => $request->satuan,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            return response()->json(['id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        try {
            DB::table('barang')->where('id', $id)->update([
                'barcode' => $request->barcode,
                'nama_barang' => $request->nama_barang,
                'kategori_id' => $request->kategori_id ?: null,
                'supplier_id' => $request->supplier_id ?: null,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'stok' => $request->stok,
                'satuan' => $request->satuan,
                'updated_at' => now()
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function apiDestroy($id)
    {
        try {
            DB::table('barang')->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
