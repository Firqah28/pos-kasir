<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pembelian;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Makassar');

        // 1. Total penjualan hari ini (SUM total_harga dari transaksi)
        $totalPenjualan = Transaksi::whereDate('created_at', $today)->sum('total_harga');

        // 2. Total modal terjual hari ini (SUM harga_modal * qty dari detail transaksi yang laku)
        $totalModalTerjual = DetailTransaksi::whereHas('transaksi', function ($q) use ($today) {
            $q->whereDate('created_at', $today);
        })->sum(\DB::raw('harga_modal * qty'));

        // 3. Keuntungan bersih = total penjualan - total modal terjual
        $keuntunganBersih = $totalPenjualan - $totalModalTerjual;

        // 4. Pengeluaran supplier hari ini (SUM total_harga dari pembelian)
        $pengeluaranSupplier = Pembelian::whereDate('created_at', $today)->sum('total_harga');

        return view('dashboard', compact(
            'totalPenjualan',
            'totalModalTerjual',
            'keuntunganBersih',
            'pengeluaranSupplier'
        ));
    }
}
