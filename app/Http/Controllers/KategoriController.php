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
        $kategori = DB::table('kategori')->get();
        return response()->json($kategori);
    }

    public function apiStore(Request $request)
    {
        try {
            $id = DB::table('kategori')->insertGetId([
                'nama_kategori' => $request->nama_kategori,
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
            DB::table('kategori')->where('id', $id)->update([
                'nama_kategori' => $request->nama_kategori,
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
            DB::table('kategori')->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
