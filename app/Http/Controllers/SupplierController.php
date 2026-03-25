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
        $supplier = DB::table('supplier')->get();
        return response()->json($supplier);
    }

    public function apiStore(Request $request)
    {
        try {
            $id = DB::table('supplier')->insertGetId([
                'nama_supplier' => $request->nama_supplier,
                'kontak' => $request->kontak,
                'alamat' => $request->alamat,
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
            DB::table('supplier')->where('id', $id)->update([
                'nama_supplier' => $request->nama_supplier,
                'kontak' => $request->kontak,
                'alamat' => $request->alamat,
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
            DB::table('supplier')->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
