<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        return view('kategori');
    }

    public function apiIndex()
    {
        $storeId = $this->currentStoreId();

        $kategori = DB::table('kategori')
            ->when($storeId, fn ($q) => $q->where('store_id', $storeId))
            ->orderBy('nama_kategori')
            ->get();

        return response()->json($kategori);
    }

    public function apiStore(Request $request)
    {
        try {
            $id = DB::table('kategori')->insertGetId([
                'store_id' => $this->currentStoreId(),
                'nama_kategori' => $request->nama_kategori,
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

            $affected = DB::table('kategori')
                ->where('id', $id)
                ->when($storeId, fn ($q) => $q->where('store_id', $storeId))
                ->update([
                    'nama_kategori' => $request->nama_kategori,
                    'updated_at' => now(),
                ]);

            if ($affected === 0) {
                return response()->json(['error' => 'Kategori tidak ditemukan'], 404);
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

            $affected = DB::table('kategori')
                ->where('id', $id)
                ->when($storeId, fn ($q) => $q->where('store_id', $storeId))
                ->delete();

            if ($affected === 0) {
                return response()->json(['error' => 'Kategori tidak ditemukan'], 404);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
