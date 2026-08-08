<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::withCount(['users', 'barangs'])
            ->withSum(['transaksis'], 'total_harga')
            ->orderBy('nama_toko')
            ->get();

        return view('pusat.toko', compact('stores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_toko' => ['required', 'string', 'max:20', 'unique:stores,kode_toko'],
            'nama_toko' => ['required', 'string', 'max:150'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'fee_persen' => ['sometimes', 'numeric', 'min:0', 'max:100'],
        ]);

        Store::create($validated + ['status' => Store::STATUS_MENUNGGU_PEMBAYARAN]);

        return redirect()->route('pusat.toko')->with('success', 'Cabang toko berhasil ditambahkan. Toko akan aktif setelah pembayaran fee dikonfirmasi.');
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $validated = $request->validate([
            'kode_toko' => ['required', 'string', 'max:20', 'unique:stores,kode_toko,'.$id],
            'nama_toko' => ['required', 'string', 'max:150'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'fee_persen' => ['sometimes', 'numeric', 'min:0', 'max:100'],
        ]);

        $store->update($validated);

        return redirect()->route('pusat.toko')->with('success', 'Cabang toko berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);

        if (User::where('store_id', $id)->exists()) {
            return redirect()->route('pusat.toko')
                ->withErrors(['error' => 'Cabang masih memiliki pengguna. Hapus pengguna terlebih dahulu.']);
        }

        $store->delete();

        return redirect()->route('pusat.toko')->with('success', 'Cabang toko berhasil dihapus.');
    }
}
