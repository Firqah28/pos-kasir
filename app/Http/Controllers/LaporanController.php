<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan');
    }

    public function apiHarian(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $whereClause = "";
        $params = [];
        if ($startDate && $endDate) {
            $whereClause = "WHERE DATE(created_at) BETWEEN ? AND ?";
            $params = [$startDate, $endDate, $startDate, $endDate];
        }

        $query = "
            SELECT 
                tanggal,
                SUM(total_transaksi) as total_transaksi,
                SUM(total_penjualan) as total_penjualan,
                SUM(total_pembelian) as total_pembelian,
                (SUM(total_penjualan) - SUM(total_pembelian)) as keuntungan
            FROM (
                SELECT DATE(created_at) as tanggal, COUNT(id) as total_transaksi, SUM(total_harga) as total_penjualan, 0 as total_pembelian
                FROM transaksi
                $whereClause
                GROUP BY DATE(created_at)
                UNION ALL
                SELECT DATE(created_at) as tanggal, 0 as total_transaksi, 0 as total_penjualan, SUM(total_harga) as total_pembelian
                FROM pembelian
                $whereClause
                GROUP BY DATE(created_at)
            ) as daily_summary
            GROUP BY tanggal
            ORDER BY tanggal DESC
        ";

        $results = DB::select($query, $params);
        return response()->json($results);
    }

    public function apiBulanan(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $whereClause = "";
        $params = [];
        if ($startDate && $endDate) {
            $whereClause = "WHERE DATE(created_at) BETWEEN ? AND ?";
            $params = [$startDate, $endDate, $startDate, $endDate];
        }

        $query = "
            SELECT 
                bulan,
                SUM(total_transaksi) as total_transaksi,
                SUM(total_penjualan) as total_penjualan,
                SUM(total_pembelian) as total_pembelian,
                (SUM(total_penjualan) - SUM(total_pembelian)) as keuntungan
            FROM (
                SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(id) as total_transaksi, SUM(total_harga) as total_penjualan, 0 as total_pembelian
                FROM transaksi
                $whereClause
                GROUP BY bulan
                UNION ALL
                SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan, 0 as total_transaksi, 0 as total_penjualan, SUM(total_harga) as total_pembelian
                FROM pembelian
                $whereClause
                GROUP BY bulan
            ) as monthly_summary
            GROUP BY bulan
            ORDER BY bulan DESC
        ";

        $results = DB::select($query, $params);
        return response()->json($results);
    }

    public function apiTahunan(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $whereClause = "";
        $params = [];
        if ($startDate && $endDate) {
            $whereClause = "WHERE DATE(created_at) BETWEEN ? AND ?";
            $params = [$startDate, $endDate, $startDate, $endDate];
        }

        $query = "
            SELECT 
                tahun,
                SUM(total_transaksi) as total_transaksi,
                SUM(total_penjualan) as total_penjualan,
                SUM(total_pembelian) as total_pembelian,
                (SUM(total_penjualan) - SUM(total_pembelian)) as keuntungan
            FROM (
                SELECT DATE_FORMAT(created_at, '%Y') as tahun, COUNT(id) as total_transaksi, SUM(total_harga) as total_penjualan, 0 as total_pembelian
                FROM transaksi
                $whereClause
                GROUP BY tahun
                UNION ALL
                SELECT DATE_FORMAT(created_at, '%Y') as tahun, 0 as total_transaksi, 0 as total_penjualan, SUM(total_harga) as total_pembelian
                FROM pembelian
                $whereClause
                GROUP BY tahun
            ) as yearly_summary
            GROUP BY tahun
            ORDER BY tahun DESC
        ";

        $results = DB::select($query, $params);
        return response()->json($results);
    }

    public function apiPerjam(Request $request)
    {
        $date = $request->query('date');

        $query = "
            SELECT 
                DATE_FORMAT(tanggal_asli, '%Y-%m-%d %H:00:00') as jam,
                SUM(total_transaksi) as total_transaksi,
                SUM(total_penjualan) as total_penjualan,
                SUM(total_pembelian) as total_pembelian,
                (SUM(total_penjualan) - SUM(total_pembelian)) as keuntungan
            FROM (
                SELECT created_at as tanggal_asli, 1 as total_transaksi, total_harga as total_penjualan, 0 as total_pembelian
                FROM transaksi
                WHERE DATE(created_at) = ?
                UNION ALL
                SELECT created_at as tanggal_asli, 0 as total_transaksi, 0 as total_penjualan, total_harga as total_pembelian
                FROM pembelian
                WHERE DATE(created_at) = ?
            ) as hourly_summary
            GROUP BY jam
            ORDER BY jam ASC
        ";

        $results = DB::select($query, [$date, $date]);
        return response()->json($results);
    }

    public function apiHistoryPenjualan(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $query = DB::table('transaksi as t')
            ->leftJoin('users as u', 't.user_id', '=', 'u.id')
            ->select('t.id', 't.total_harga', 't.bayar', 't.kembalian', 't.created_at', 'u.username as kasir_name');
            
        if ($startDate && $endDate) {
            $query->whereRaw('DATE(t.created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
        }

        $results = $query->orderByDesc('t.created_at')->get();
        return response()->json($results);
    }

    public function apiHistoryPenjualanDetail($id)
    {
        $header = DB::table('transaksi as t')
            ->leftJoin('users as u', 't.user_id', '=', 'u.id')
            ->select('t.id', 't.total_harga', 't.bayar', 't.kembalian', 't.created_at', 'u.username as kasir_name')
            ->where('t.id', $id)
            ->first();

        if (!$header) return response()->json(['error' => 'Not found'], 404);

        $items = DB::table('detail_transaksi as dt')
            ->leftJoin('barang as b', 'dt.barang_id', '=', 'b.id')
            ->select('dt.qty', 'dt.harga_jual as harga', 'dt.subtotal', 'b.nama_barang')
            ->where('dt.transaksi_id', $id)
            ->get();

        return response()->json(['transaksi' => $header, 'items' => $items]);
    }

    public function apiHistoryPembelian(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $query = DB::table('pembelian as p')
            ->leftJoin('users as u', 'p.user_id', '=', 'u.id')
            ->leftJoin('supplier as s', 'p.supplier_id', '=', 's.id')
            ->select('p.id', 'p.total_harga', 'p.created_at', 'u.username as admin_name', 's.nama_supplier');

        if ($startDate && $endDate) {
            $query->whereRaw('DATE(p.created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
        }

        $results = $query->orderByDesc('p.created_at')->get();
        return response()->json($results);
    }

    public function apiHistoryPembelianDetail($id)
    {
        $header = DB::table('pembelian as p')
            ->leftJoin('users as u', 'p.user_id', '=', 'u.id')
            ->leftJoin('supplier as s', 'p.supplier_id', '=', 's.id')
            ->select('p.id', 'p.total_harga', 'p.created_at', 'u.username as admin_name', 's.nama_supplier')
            ->where('p.id', $id)
            ->first();

        if (!$header) return response()->json(['error' => 'Not found'], 404);

        $items = DB::table('detail_pembelian as dp')
            ->leftJoin('barang as b', 'dp.barang_id', '=', 'b.id')
            ->select('dp.qty', 'dp.harga_beli', 'dp.subtotal', 'b.nama_barang')
            ->where('dp.pembelian_id', $id)
            ->get();

        return response()->json(['transaksi' => $header, 'items' => $items]);
    }

    public function apiLaporanBarang(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $query = DB::table('detail_transaksi as dt')
            ->join('barang as b', 'dt.barang_id', '=', 'b.id')
            ->join('transaksi as t', 'dt.transaksi_id', '=', 't.id')
            ->select('b.nama_barang', DB::raw('SUM(dt.qty) as total_qty'), DB::raw('SUM(dt.subtotal) as total_pendapatan'));

        if ($startDate && $endDate) {
            $query->whereRaw('DATE(t.created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
        }

        $results = $query->groupBy('dt.barang_id', 'b.nama_barang')
                         ->orderByDesc('total_qty')
                         ->get();

        return response()->json($results);
    }
}
