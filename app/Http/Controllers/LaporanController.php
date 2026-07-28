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

    private function getDateFilterSql($tableAlias, $startDate, $endDate)
    {
        if (!$startDate || !$endDate) return ['', []];
        return ["WHERE DATE({$tableAlias}.created_at) BETWEEN ? AND ?", [$startDate, $endDate]];
    }

    private function mergePeriods($penjualan, $hpp, $pembelian, $periodKey)
    {
        $allPeriods = array_unique(array_merge(
            array_column($penjualan, $periodKey),
            array_column($hpp, $periodKey),
            array_column($pembelian, $periodKey)
        ));
        rsort($allPeriods);

        $penMap = [];
        foreach ($penjualan as $row) $penMap[$row->$periodKey] = $row;
        $hppMap = [];
        foreach ($hpp as $row) $hppMap[$row->$periodKey] = $row;
        $belMap = [];
        foreach ($pembelian as $row) $belMap[$row->$periodKey] = $row;

        $results = [];
        foreach ($allPeriods as $period) {
            $p = $penMap[$period] ?? null;
            $h = $hppMap[$period] ?? null;
            $b = $belMap[$period] ?? null;
            $totalPenjualan = (float) ($p->total_penjualan ?? 0);
            $totalHpp = (float) ($h->total_hpp ?? 0);
            $results[] = (object) [
                $periodKey => $period,
                'total_transaksi' => (int) ($p->total_transaksi ?? 0),
                'total_penjualan' => $totalPenjualan,
                'total_hpp' => $totalHpp,
                'keuntungan' => $totalPenjualan - $totalHpp,
                'total_pembelian' => (float) ($b->total_pembelian ?? 0),
            ];
        }
        return $results;
    }

    public function apiHarian(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        [$whereT, $paramsT] = $this->getDateFilterSql('t', $startDate, $endDate);
        [$whereDt, $paramsDt] = $this->getDateFilterSql('t2', $startDate, $endDate);
        [$whereP, $paramsP] = $this->getDateFilterSql('p', $startDate, $endDate);

        $penjualan = DB::select(
            "SELECT DATE(t.created_at) as tanggal, COUNT(t.id) as total_transaksi, SUM(t.total_harga) as total_penjualan FROM transaksi t {$whereT} GROUP BY tanggal",
            $paramsT
        );
        $hpp = DB::select(
            "SELECT DATE(t2.created_at) as tanggal, SUM(dt.harga_modal * dt.qty) as total_hpp FROM detail_transaksi dt JOIN transaksi t2 ON dt.transaksi_id = t2.id {$whereDt} GROUP BY tanggal",
            $paramsDt
        );
        $pembelian = DB::select(
            "SELECT DATE(p.created_at) as tanggal, SUM(p.total_harga) as total_pembelian FROM pembelian p {$whereP} GROUP BY tanggal",
            $paramsP
        );

        return response()->json($this->mergePeriods($penjualan, $hpp, $pembelian, 'tanggal'));
    }

    public function apiBulanan(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        [$whereT, $paramsT] = $this->getDateFilterSql('t', $startDate, $endDate);
        [$whereDt, $paramsDt] = $this->getDateFilterSql('t2', $startDate, $endDate);
        [$whereP, $paramsP] = $this->getDateFilterSql('p', $startDate, $endDate);

        $penjualan = DB::select(
            "SELECT DATE_FORMAT(t.created_at, '%Y-%m') as bulan, COUNT(t.id) as total_transaksi, SUM(t.total_harga) as total_penjualan FROM transaksi t {$whereT} GROUP BY bulan",
            $paramsT
        );
        $hpp = DB::select(
            "SELECT DATE_FORMAT(t2.created_at, '%Y-%m') as bulan, SUM(dt.harga_modal * dt.qty) as total_hpp FROM detail_transaksi dt JOIN transaksi t2 ON dt.transaksi_id = t2.id {$whereDt} GROUP BY bulan",
            $paramsDt
        );
        $pembelian = DB::select(
            "SELECT DATE_FORMAT(p.created_at, '%Y-%m') as bulan, SUM(p.total_harga) as total_pembelian FROM pembelian p {$whereP} GROUP BY bulan",
            $paramsP
        );

        return response()->json($this->mergePeriods($penjualan, $hpp, $pembelian, 'bulan'));
    }

    public function apiTahunan(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        [$whereT, $paramsT] = $this->getDateFilterSql('t', $startDate, $endDate);
        [$whereDt, $paramsDt] = $this->getDateFilterSql('t2', $startDate, $endDate);
        [$whereP, $paramsP] = $this->getDateFilterSql('p', $startDate, $endDate);

        $penjualan = DB::select(
            "SELECT DATE_FORMAT(t.created_at, '%Y') as tahun, COUNT(t.id) as total_transaksi, SUM(t.total_harga) as total_penjualan FROM transaksi t {$whereT} GROUP BY tahun",
            $paramsT
        );
        $hpp = DB::select(
            "SELECT DATE_FORMAT(t2.created_at, '%Y') as tahun, SUM(dt.harga_modal * dt.qty) as total_hpp FROM detail_transaksi dt JOIN transaksi t2 ON dt.transaksi_id = t2.id {$whereDt} GROUP BY tahun",
            $paramsDt
        );
        $pembelian = DB::select(
            "SELECT DATE_FORMAT(p.created_at, '%Y') as tahun, SUM(p.total_harga) as total_pembelian FROM pembelian p {$whereP} GROUP BY tahun",
            $paramsP
        );

        return response()->json($this->mergePeriods($penjualan, $hpp, $pembelian, 'tahun'));
    }

    public function apiPerjam(Request $request)
    {
        $date = $request->query('date');

        $penjualan = DB::select(
            "SELECT DATE_FORMAT(CONVERT_TZ(t.created_at, '+00:00', '+08:00'), '%Y-%m-%d %H:00:00') as jam,
                    COUNT(t.id) as total_transaksi, SUM(t.total_harga) as total_penjualan
             FROM transaksi t
             WHERE DATE(CONVERT_TZ(t.created_at, '+00:00', '+08:00')) = ?
             GROUP BY jam",
            [$date]
        );
        $hpp = DB::select(
            "SELECT DATE_FORMAT(CONVERT_TZ(t2.created_at, '+00:00', '+08:00'), '%Y-%m-%d %H:00:00') as jam,
                    SUM(dt.harga_modal * dt.qty) as total_hpp
             FROM detail_transaksi dt
             JOIN transaksi t2 ON dt.transaksi_id = t2.id
             WHERE DATE(CONVERT_TZ(t2.created_at, '+00:00', '+08:00')) = ?
             GROUP BY jam",
            [$date]
        );
        $pembelian = DB::select(
            "SELECT DATE_FORMAT(CONVERT_TZ(p.created_at, '+00:00', '+08:00'), '%Y-%m-%d %H:00:00') as jam,
                    SUM(p.total_harga) as total_pembelian
             FROM pembelian p
             WHERE DATE(CONVERT_TZ(p.created_at, '+00:00', '+08:00')) = ?
             GROUP BY jam",
            [$date]
        );

        return response()->json($this->mergePeriods($penjualan, $hpp, $pembelian, 'jam'));
    }

    public function apiHistoryPenjualan(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $query = DB::table('transaksi as t')
            ->leftJoin('users as u', 't.user_id', '=', 'u.id')
            ->select('t.id', 't.total_harga', 't.bayar', 't.kembalian', 't.created_at', 'u.username as kasir_name');
            
        if ($startDate && $endDate) {
            $query->whereRaw('DATE(CONVERT_TZ(t.created_at, \'+00:00\', \'+08:00\')) BETWEEN ? AND ?', [$startDate, $endDate]);
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
            ->select('dt.qty', 'dt.harga_jual as harga', 'dt.harga_modal', 'dt.subtotal', 'b.nama_barang')
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
            $query->whereRaw('DATE(CONVERT_TZ(p.created_at, \'+00:00\', \'+08:00\')) BETWEEN ? AND ?', [$startDate, $endDate]);
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
            $query->whereRaw('DATE(CONVERT_TZ(t.created_at, \'+00:00\', \'+08:00\')) BETWEEN ? AND ?', [$startDate, $endDate]);
        }

        $results = $query->groupBy('dt.barang_id', 'b.nama_barang')
                         ->orderByDesc('total_qty')
                         ->get();

        return response()->json($results);
    }
}
