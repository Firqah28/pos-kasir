<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        return view('supplier');
    }

    public function apiIndex()
    {
        $storeId = $this->currentStoreId();

        $supplier = DB::table('supplier')
            ->when($storeId, fn ($q) => $q->where('store_id', $storeId))
            ->orderBy('nama_supplier')
            ->get();

        return response()->json($supplier);
    }

    public function apiStore(Request $request)
    {
        try {
            $id = DB::table('supplier')->insertGetId([
                'store_id' => $this->currentStoreId(),
                'nama_supplier' => $request->nama_supplier,
                'kontak' => $request->kontak,
                'alamat' => $request->alamat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        try {
            $storeId = $this->currentStoreId();

            $affected = DB::table('supplier')
                ->where('id', $id)
                ->when($storeId, fn ($q) => $q->where('store_id', $storeId))
                ->update([
                    'nama_supplier' => $request->nama_supplier,
                    'kontak' => $request->kontak,
                    'alamat' => $request->alamat,
                    'updated_at' => now(),
                ]);

            if ($affected === 0) {
                return response()->json(['error' => 'Supplier tidak ditemukan'], 404);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function apiDestroy($id)
    {
        try {
            $storeId = $this->currentStoreId();

            $affected = DB::table('supplier')
                ->where('id', $id)
                ->when($storeId, fn ($q) => $q->where('store_id', $storeId))
                ->delete();

            if ($affected === 0) {
                return response()->json(['error' => 'Supplier tidak ditemukan'], 404);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
