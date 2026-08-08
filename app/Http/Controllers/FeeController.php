<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Makassar');
        $periode = $today->format('Y-m');
        $awal = $today->startOfMonth()->toDateTimeString();
        $akhir = $today->copy()->endOfMonth()->toDateTimeString();

        $penjualanPerStore = DB::table('transaksi')
            ->whereBetween('created_at', [$awal, $akhir])
            ->whereNotNull('store_id')
            ->groupBy('store_id')
            ->select('store_id', DB::raw('SUM(total_harga) as total'))
            ->pluck('total', 'store_id');

        $bills = DB::table('store_fees')
            ->where('periode', $periode)
            ->get()
            ->keyBy('store_id');

        $stores = Store::orderBy('nama_toko')->get()->map(function (Store $store) use ($penjualanPerStore, $bills) {
            $penjualan = (float) ($penjualanPerStore[$store->id] ?? 0);
            $feeAmount = round(($penjualan * ($store->fee_persen ?? 5)) / 100, 2);

            $bill = $bills->get($store->id);

            return [
                'store' => $store,
                'penjualan' => $penjualan,
                'fee_amount' => $feeAmount,
                'bill_status' => $bill?->status ?? 'pending',
                'paid_at' => $bill?->paid_at,
                'confirmed_by' => $bill ? User::find($bill->confirmed_by)?->username : null,
            ];
        });

        return view('pusat.fee', compact('stores', 'periode'));
    }

    /**
     * Pengaturan status fee dilakukan manual oleh admin super.
     */
    public function updateStatus(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:aktif,menunggu_pembayaran,nonaktif'],
        ]);

        $status = $validated['status'];

        $today = Carbon::today('Asia/Makassar');
        $periode = $today->format('Y-m');

        if ($status === Store::STATUS_AKTIF) {
            $penjualan = (float) DB::table('transaksi')
                ->where('store_id', $store->id)
                ->whereBetween('created_at', [$today->startOfMonth()->toDateTimeString(), $today->copy()->endOfMonth()->toDateTimeString()])
                ->sum('total_harga');

            $feeAmount = round(($penjualan * ($store->fee_persen ?? 5)) / 100, 2);

            DB::table('store_fees')->updateOrInsert(
                ['store_id' => $store->id, 'periode' => $periode],
                [
                    'fee_amount' => $feeAmount,
                    'status' => 'paid',
                    'paid_at' => now(),
                    'confirmed_by' => auth()->id(),
                    'updated_at' => now(),
                ]
            );
        } elseif ($status === Store::STATUS_MENUNGGU_PEMBAYARAN) {
            DB::table('store_fees')->updateOrInsert(
                ['store_id' => $store->id, 'periode' => $periode],
                [
                    'status' => 'pending',
                    'paid_at' => null,
                    'updated_at' => now(),
                ]
            );
        }

        $store->update(['status' => $status]);

        $pesan = match ($status) {
            Store::STATUS_AKTIF => "Status {$store->nama_toko} diubah menjadi Aktif (fee periode {$periode} dianggap lunas).",
            Store::STATUS_MENUNGGU_PEMBAYARAN => "Status {$store->nama_toko} diubah menjadi Menunggu Pembayaran.",
            Store::STATUS_NONAKTIF => "Status {$store->nama_toko} diubah menjadi Nonaktif.",
        };

        return redirect()->route('pusat.fee')->with('success', $pesan);
    }
}
