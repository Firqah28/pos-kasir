<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $feePersen = 0;
        $storeId = $this->currentStoreId();

        if ($storeId) {
            $store = Store::find($storeId);
            $feePersen = $store?->fee_persen ?? 0;
        }

        return view('laporan', compact('feePersen'));
    }

    private function getDateFilterSql($tableAlias, $startDate, $endDate)
    {
        if (! $startDate || ! $endDate) {
            return ['', []];
        }

        return ["DATE({$tableAlias}.created_at) BETWEEN ? AND ?", [$startDate, $endDate]];
    }

    private function getStoreFilterSql($tableAlias)
    {
        $storeId = $this->currentStoreId();
        if (! $storeId) {
            return ['', []];
        }

        return ["{$tableAlias}.store_id = ?", [$storeId]];
    }

    private function buildWhereSql(array $conditions)
    {
        $parts = [];
        $params = [];

        foreach ($conditions as [$clause, $bindings]) {
            if ($clause) {
                $parts[] = $clause;
                foreach ($bindings as $binding) {
                    $params[] = $binding;
                }
            }
        }

        return [empty($parts) ? '' : 'WHERE '.implode(' AND ', $parts), $params];
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
        foreach ($penjualan as $row) {
            $penMap[$row->$periodKey] = $row;
        }
        $hppMap = [];
        foreach ($hpp as $row) {
            $hppMap[$row->$periodKey] = $row;
        }
        $belMap = [];
        foreach ($pembelian as $row) {
            $belMap[$row->$periodKey] = $row;
        }

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

    private function periodQuery($penDateClause, $hppDateClause, $belDateClause, $penExpr, $hppExpr, $belExpr, $groupCol)
    {
        [$whereT, $paramsT] = $this->buildWhereSql([
            $penDateClause,
            $this->getStoreFilterSql('t'),
        ]);
        [$whereDt, $paramsDt] = $this->buildWhereSql([
            $hppDateClause,
            $this->getStoreFilterSql('t2'),
        ]);
        [$whereP, $paramsP] = $this->buildWhereSql([
            $belDateClause,
            $this->getStoreFilterSql('p'),
        ]);

        $penjualan = DB::select(
            "SELECT {$penExpr} as {$groupCol}, COUNT(t.id) as total_transaksi, SUM(t.total_harga) as total_penjualan FROM transaksi t {$whereT} GROUP BY {$groupCol}",
            $paramsT
        );
        $hpp = DB::select(
            "SELECT {$hppExpr} as {$groupCol}, SUM(dt.harga_modal * dt.qty) as total_hpp FROM detail_transaksi dt JOIN transaksi t2 ON dt.transaksi_id = t2.id {$whereDt} GROUP BY {$groupCol}",
            $paramsDt
        );
        $pembelian = DB::select(
            "SELECT {$belExpr} as {$groupCol}, SUM(p.total_harga) as total_pembelian FROM pembelian p {$whereP} GROUP BY {$groupCol}",
            $paramsP
        );

        return [$penjualan, $hpp, $pembelian];
    }

    public function apiHarian(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        [$penjualan, $hpp, $pembelian] = $this->periodQuery(
            $this->getDateFilterSql('t', $startDate, $endDate),
            $this->getDateFilterSql('t2', $startDate, $endDate),
            $this->getDateFilterSql('p', $startDate, $endDate),
            'DATE(t.created_at)', 'DATE(t2.created_at)', 'DATE(p.created_at)',
            'tanggal'
        );

        return response()->json($this->mergePeriods($penjualan, $hpp, $pembelian, 'tanggal'));
    }

    public function apiBulanan(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        [$penjualan, $hpp, $pembelian] = $this->periodQuery(
            $this->getDateFilterSql('t', $startDate, $endDate),
            $this->getDateFilterSql('t2', $startDate, $endDate),
            $this->getDateFilterSql('p', $startDate, $endDate),
            "DATE_FORMAT(t.created_at, '%Y-%m')", "DATE_FORMAT(t2.created_at, '%Y-%m')", "DATE_FORMAT(p.created_at, '%Y-%m')",
            'bulan'
        );

        return response()->json($this->mergePeriods($penjualan, $hpp, $pembelian, 'bulan'));
    }

    public function apiTahunan(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        [$penjualan, $hpp, $pembelian] = $this->periodQuery(
            $this->getDateFilterSql('t', $startDate, $endDate),
            $this->getDateFilterSql('t2', $startDate, $endDate),
            $this->getDateFilterSql('p', $startDate, $endDate),
            "DATE_FORMAT(t.created_at, '%Y')", "DATE_FORMAT(t2.created_at, '%Y')", "DATE_FORMAT(p.created_at, '%Y')",
            'tahun'
        );

        return response()->json($this->mergePeriods($penjualan, $hpp, $pembelian, 'tahun'));
    }

    public function apiPerjam(Request $request)
    {
        $date = $request->query('date');

        [$penjualan, $hpp, $pembelian] = $this->periodQuery(
            ['DATE(t.created_at) = ?', [$date]],
            ['DATE(t2.created_at) = ?', [$date]],
            ['DATE(p.created_at) = ?', [$date]],
            "DATE_FORMAT(t.created_at, '%Y-%m-%d %H:00:00')", "DATE_FORMAT(t2.created_at, '%Y-%m-%d %H:00:00')", "DATE_FORMAT(p.created_at, '%Y-%m-%d %H:00:00')",
            'jam'
        );

        return response()->json($this->mergePeriods($penjualan, $hpp, $pembelian, 'jam'));
    }

    public function apiHistoryPenjualan(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $query = DB::table('transaksi as t')
            ->leftJoin('users as u', 't.user_id', '=', 'u.id')
            ->select('t.id', 't.total_harga', 't.bayar', 't.kembalian', 't.created_at', 'u.username as kasir_name')
            ->when($this->currentStoreId(), fn ($q, $storeId) => $q->where('t.store_id', $storeId));

        if ($startDate && $endDate) {
            $query->whereRaw('DATE(t.created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
        }

        $results = $query->orderByDesc('t.created_at')->get();

        return response()->json($results);
    }

    public function apiHistoryPenjualanDetail($id)
    {
        $storeId = $this->currentStoreId();

        $header = DB::table('transaksi as t')
            ->leftJoin('users as u', 't.user_id', '=', 'u.id')
            ->select('t.id', 't.total_harga', 't.bayar', 't.kembalian', 't.created_at', 'u.username as kasir_name')
            ->where('t.id', $id)
            ->when($storeId, fn ($q, $sid) => $q->where('t.store_id', $sid))
            ->first();

        if (! $header) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $items = DB::table('detail_transaksi as dt')
            ->leftJoin('barang as b', 'dt.barang_id', '=', 'b.id')
            ->select('dt.qty', 'dt.harga_jual as harga', 'dt.harga_modal', 'dt.subtotal', 'b.nama_barang')
            ->where('dt.transaksi_id', $id)
            ->when($storeId, fn ($q, $sid) => $q->where('dt.store_id', $sid))
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
            ->select('p.id', 'p.total_harga', 'p.created_at', 'u.username as admin_name', 's.nama_supplier')
            ->when($this->currentStoreId(), fn ($q, $storeId) => $q->where('p.store_id', $storeId));

        if ($startDate && $endDate) {
            $query->whereRaw('DATE(p.created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
        }

        $results = $query->orderByDesc('p.created_at')->get();

        return response()->json($results);
    }

    public function apiHistoryPembelianDetail($id)
    {
        $storeId = $this->currentStoreId();

        $header = DB::table('pembelian as p')
            ->leftJoin('users as u', 'p.user_id', '=', 'u.id')
            ->leftJoin('supplier as s', 'p.supplier_id', '=', 's.id')
            ->select('p.id', 'p.total_harga', 'p.created_at', 'u.username as admin_name', 's.nama_supplier')
            ->where('p.id', $id)
            ->when($storeId, fn ($q, $sid) => $q->where('p.store_id', $sid))
            ->first();

        if (! $header) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $items = DB::table('detail_pembelian as dp')
            ->leftJoin('barang as b', 'dp.barang_id', '=', 'b.id')
            ->select('dp.qty', 'dp.harga_beli', 'dp.subtotal', 'b.nama_barang')
            ->where('dp.pembelian_id', $id)
            ->when($storeId, fn ($q, $sid) => $q->where('dp.store_id', $sid))
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
            ->select('b.nama_barang', DB::raw('SUM(dt.qty) as total_qty'), DB::raw('SUM(dt.subtotal) as total_pendapatan'))
            ->when($this->currentStoreId(), fn ($q, $storeId) => $q->where('t.store_id', $storeId));

        if ($startDate && $endDate) {
            $query->whereRaw('DATE(t.created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
        }

        $results = $query->groupBy('dt.barang_id', 'b.nama_barang')
            ->orderByDesc('total_qty')
            ->get();

        return response()->json($results);
    }
}
