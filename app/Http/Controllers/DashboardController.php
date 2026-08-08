<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Pembelian;
use App\Models\Store;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Makassar');
        $storeId = $this->currentStoreId();

        // 1. Total penjualan hari ini (SUM total_harga dari transaksi)
        $totalPenjualan = Transaksi::whereDate('created_at', $today)
            ->when($storeId, fn ($q) => $q->where('store_id', $storeId))
            ->sum('total_harga');

        // 2. Total modal terjual hari ini (SUM harga_modal * qty dari detail transaksi yang laku)
        $totalModalTerjual = DetailTransaksi::when($storeId, fn ($q) => $q->where('store_id', $storeId))
            ->whereHas('transaksi', function ($q) use ($today, $storeId) {
                $q->whereDate('created_at', $today)
                    ->when($storeId, fn ($w) => $w->where('store_id', $storeId));
            })
            ->sum(\DB::raw('harga_modal * qty'));

        // 3. Keuntungan bersih = total penjualan - total modal terjual
        $keuntunganBersih = $totalPenjualan - $totalModalTerjual;

        // 4. Pengeluaran supplier hari ini (SUM total_harga dari pembelian)
        $pengeluaranSupplier = Pembelian::whereDate('created_at', $today)
            ->when($storeId, fn ($q) => $q->where('store_id', $storeId))
            ->sum('total_harga');

        return view('dashboard', compact(
            'totalPenjualan',
            'totalModalTerjual',
            'keuntunganBersih',
            'pengeluaranSupplier'
        ));
    }

    /**
     * Dashboard Pusat (Master Admin) - rekap seluruh cabang.
     */
    public function pusat(Request $request)
    {
        $today = Carbon::today('Asia/Makassar');
        $period = $request->query('period', 'harian');

        $dateParam = $request->query('date');
        $date = ($dateParam && Carbon::hasFormat((string) $dateParam, 'Y-m-d'))
            ? Carbon::parse($dateParam)
            : $today;

        switch ($period) {
            case 'bulanan':
                $start = $date->copy()->startOfMonth();
                $end = $date->copy()->endOfMonth();
                break;
            case 'tahunan':
                $start = $date->copy()->startOfYear();
                $end = $date->copy()->endOfYear();
                break;
            default:
                $period = 'harian';
                $start = $date->copy()->startOfDay();
                $end = $date->copy()->endOfDay();
        }

        $startStr = $start->toDateTimeString();
        $endStr = $end->toDateTimeString();

        $totalToko = Store::where('status', Store::STATUS_AKTIF)->count();
        $totalPenjualan = Transaksi::whereBetween('created_at', [$startStr, $endStr])->sum('total_harga');
        $totalPembelian = Pembelian::whereBetween('created_at', [$startStr, $endStr])->sum('total_harga');

        $stores = Store::withCount('users')
            ->withSum(['transaksis' => fn ($q) => $q->whereBetween('created_at', [$startStr, $endStr])], 'total_harga')
            ->get();

        $modalPerStore = \DB::table('detail_transaksi as dt')
            ->join('transaksi as t', 'dt.transaksi_id', '=', 't.id')
            ->whereBetween('t.created_at', [$startStr, $endStr])
            ->groupBy('t.store_id')
            ->select('t.store_id', \DB::raw('SUM(dt.harga_modal * dt.qty) as total_modal'))
            ->pluck('total_modal', 'store_id');

        $stores->each(function ($store) use ($modalPerStore) {
            $penjualan = (float) ($store->transaksis_sum_total_harga ?? 0);
            $modal = (float) ($modalPerStore[$store->id] ?? 0);
            $store->keuntungan = $penjualan - $modal;
            $store->keuntungan_persen = $penjualan > 0
                ? round(($store->keuntungan / $penjualan) * 100, 1)
                : 0;

            $store->fee_persen_show = $store->fee_persen ?? 5;
            $store->fee = round(($penjualan * $store->fee_persen_show) / 100, 2);
        });

        $stores = $stores->sortByDesc('keuntungan')->values();

        $totalKeuntungan = $stores->sum('keuntungan');
        $totalKeuntunganPersen = $totalPenjualan > 0
            ? round(($totalKeuntungan / $totalPenjualan) * 100, 1)
            : 0;
        $totalFee = $stores->sum('fee');

        return view('dashboard-pusat', compact(
            'totalToko',
            'totalPenjualan',
            'totalPembelian',
            'totalKeuntungan',
            'totalKeuntunganPersen',
            'totalFee',
            'stores',
            'period',
            'date'
        ));
    }

    /**
     * Master Admin "masuk" ke detail sebuah cabang (preview).
     */
    public function previewEnter(Store $store)
    {
        if (! $store->isAktif()) {
            return redirect()->route('dashboard.pusat')->with('error', 'Cabang toko tidak aktif.');
        }

        session(['preview_store_id' => $store->id]);

        return redirect()->route('dashboard');
    }

    /**
     * Keluar dari preview cabang, kembali ke dashboard pusat.
     */
    public function previewExit()
    {
        session()->forget('preview_store_id');

        return redirect()->route('dashboard.pusat');
    }
}
