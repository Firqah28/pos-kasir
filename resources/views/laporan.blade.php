@extends('layouts.app')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Laporan Penjualan</h1>
            <p class="mt-2 text-sm text-gray-600">Analisa performa penjualan toko Anda secara real-time.</p>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap items-center gap-3">
            <div class="flex items-center space-x-2 bg-white px-2 py-1 rounded-md border border-gray-300">
                <span class="text-xs text-gray-500 font-medium">Tanggal:</span>
                <input type="date" id="filterDate" onchange="loadLaporan()" class="block border-0 py-1.5 text-gray-900 focus:ring-0 sm:text-sm sm:leading-6 text-xs">
            </div>
            <select id="periodSelect" onchange="switchPeriod()" class="block w-40 rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                <option value="harian">Harian</option>
                <option value="bulanan">Bulanan</option>
                <option value="tahunan">Tahunan</option>
            </select>
            <button onclick="printRekapan()" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm border border-gray-300 hover:bg-gray-50">
                <svg class="-ml-0.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0v3.396c0 .662.538 1.2 1.2 1.2h8.1c.662 0 1.2-.538 1.2-1.2V9.034z" />
                </svg>
                Cetak
            </button>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4">
            <div class="bg-indigo-50 p-3 rounded-lg">
                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Penjualan</p>
                <p id="statTotalSales" class="text-2xl font-bold text-gray-900">Rp 0</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4">
            <div class="bg-amber-50 p-3 rounded-lg">
                <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H3V10.5z" /></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">HPP (Modal Terjual)</p>
                <p id="statTotalHpp" class="text-2xl font-bold text-gray-900">Rp 0</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4">
            <div class="bg-green-50 p-3 rounded-lg">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Keuntungan Bersih</p>
                <p id="statTotalProfit" class="text-2xl font-bold text-green-600">Rp 0</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4">
            <div class="bg-red-50 p-3 rounded-lg">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" /></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pengeluaran Supplier</p>
                <p id="statTotalPembelian" class="text-2xl font-bold text-gray-900">Rp 0</p>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900" id="chartTitle">Analisa Penjualan & Pendapatan</h3>
        </div>
        <div id="combinedSalesChart" style="height: 380px; width: 100%;"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- History Penjualan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">History Penjualan</h3>
            </div>
            <div class="responsive-table-wrapper">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kasir</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="historyPenjualanBody" class="divide-y divide-gray-100">
                    </tbody>
                </table>
            </div>
        </div>

        <!-- History Pembelian -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">History Pembelian</h3>
            </div>
            <div class="responsive-table-wrapper">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Admin</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Supplier</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total Pembelian</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody" class="divide-y divide-gray-100">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receiptModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDetailModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div id="pembelianPrintTarget" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Struk Pembelian</h3>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="printReceipt('pembelian')" class="text-indigo-600 hover:text-indigo-900 focus:outline-none flex items-center gap-1 text-sm font-medium print:hidden">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0v3.396c0 .662.538 1.2 1.2 1.2h8.1c.662 0 1.2-.538 1.2-1.2V9.034z" />
                                </svg>
                                Cetak
                            </button>
                            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none print:hidden">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="bg-white px-4 py-5 sm:p-6 text-sm">
                    <!-- Header Info -->
                    <div class="mb-4 text-gray-600">
                        <p><strong>Tanggal:</strong> <span id="detailTanggal"></span></p>
                        <p><strong>Admin:</strong> <span id="detailAdmin"></span></p>
                        <p><strong>Supplier:</strong> <span id="detailSupplier"></span></p>
                    </div>
                    
                    <div class="border-t border-dashed border-gray-300 py-4 my-4">
                        <table class="w-full text-left text-gray-600">
                            <thead>
                                <tr>
                                    <th class="pb-2 font-medium">Barang</th>
                                    <th class="pb-2 font-medium text-right">Qty</th>
                                    <th class="pb-2 font-medium text-right">Harga</th>
                                    <th class="pb-2 font-medium text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detailItemsBody" class="divide-y divide-gray-100">
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="flex justify-between items-center font-bold text-lg text-gray-900 mt-4">
                        <span>Total:</span>
                        <span id="detailTotal" class="text-red-600"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Penjualan Receipt Modal -->
<div id="receiptPenjualanModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDetailPenjualanModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div id="penjualanPrintTarget" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">{{ $globalSettings['store_name'] ?? 'KIOS PUTRA TUNGGAL' }}</h3>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="printReceipt('penjualan')" class="text-indigo-600 hover:text-indigo-900 focus:outline-none flex items-center gap-1 text-sm font-medium print:hidden">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0v3.396c0 .662.538 1.2 1.2 1.2h8.1c.662 0 1.2-.538 1.2-1.2V9.034z" />
                                </svg>
                                Cetak
                            </button>
                            <button type="button" onclick="closeDetailPenjualanModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none print:hidden">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="bg-white px-4 py-5 sm:p-6 text-sm">
                    <!-- Header Info -->
                    <div class="mb-4 text-gray-600">
                        <p><strong>Tanggal:</strong> <span id="detailPenjualanTanggal"></span></p>
                        <p><strong>Kasir:</strong> <span id="detailPenjualanKasir"></span></p>
                    </div>
                    
                    <div class="border-t border-dashed border-gray-300 py-4 my-4">
                        <table class="w-full text-left text-gray-600">
                            <thead>
                                <tr>
                                    <th class="pb-2 font-medium">Barang</th>
                                    <th class="pb-2 font-medium text-right">Qty</th>
                                    <th class="pb-2 font-medium text-right">Harga</th>
                                    <th class="pb-2 font-medium text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detailPenjualanItemsBody" class="divide-y divide-gray-100">
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="flex justify-between items-center font-bold text-lg text-gray-900 mt-4 border-b border-dashed border-gray-300 pb-2">
                        <span>Total:</span>
                        <span id="detailPenjualanTotal" class="text-indigo-600"></span>
                    </div>
                    <div class="flex justify-between items-center text-gray-600 mt-2">
                        <span>Bayar:</span>
                        <span id="detailPenjualanBayar"></span>
                    </div>
                    <div class="flex justify-between items-center text-gray-600 mt-1">
                        <span>Kembalian:</span>
                        <span id="detailPenjualanKembalian"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<span id="storeNameDisplay" class="hidden">{{ $globalSettings['store_name'] ?? 'KIOS PUTRA TUNGGAL' }}</span>

<style>
    @media print {
        @page { size: A4; margin: 0.5cm; }
        
        body.is-printing * {
            visibility: hidden;
        }
        
        body.is-printing #printArea, 
        body.is-printing #printArea * {
            visibility: visible;
        }
        
        body.is-printing #printArea {
            visibility: visible !important;
            display: block !important;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: white;
            color: black;
        }

        /* Hide elements that offset the print area */
        body.is-printing #sidebar,
        body.is-printing .sticky.top-0,
        body.is-printing .mb-8,
        body.is-printing .grid-cols-1.lg\:grid-cols-2 {
            display: none !important;
        }

        /* Free the layout from scrolling/height restrictions so multi-page works */
        body.is-printing .h-screen,
        body.is-printing .h-full,
        body.is-printing .overflow-hidden,
        body.is-printing .overflow-y-auto {
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
        }

        body.is-printing .main-content-wrapper {
            margin-left: 0 !important;
        }

        .print-header { border-bottom: 2px solid #374151; margin-bottom: 2rem; padding-bottom: 1rem; }
        .print-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .print-stat-card { padding: 1rem; border: 1px solid #e5e7eb; background-color: #f9fafb !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; font-size: 10pt; }
        th, td { border: 1px solid #d1d5db; padding: 8px 12px; text-align: left; }
        th { background-color: #f3f4f6 !important; font-weight: bold; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-green-600 { color: #059669 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .text-red-600 { color: #dc2626 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .text-indigo-600 { color: #4f46e5 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>

<div id="printArea" class="hidden">
    <div class="print-header flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $globalSettings['store_name'] ?? 'Laporan' }}</h1>
            <p class="text-gray-600">Laporan Operasional Toko</p>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-bold text-gray-800" id="printTitle">Rekapan Laporan</h2>
            <p class="text-sm text-gray-500" id="printDate"></p>
        </div>
    </div>

    <div class="print-grid">
        <div class="print-stat-card">
            <p class="text-xs uppercase text-gray-500 font-semibold mb-1">Total Penjualan</p>
            <p id="printTotalSales" class="text-xl font-bold text-gray-900"></p>
        </div>
        <div class="print-stat-card">
            <p class="text-xs uppercase text-gray-500 font-semibold mb-1">HPP (Modal Terjual)</p>
            <p id="printTotalHpp" class="text-xl font-bold text-gray-900"></p>
        </div>
        <div class="print-stat-card">
            <p class="text-xs uppercase text-gray-500 font-semibold mb-1">Keuntungan Bersih</p>
            <p id="printTotalProfit" class="text-xl font-bold text-green-600"></p>
        </div>
        <div class="print-stat-card">
            <p class="text-xs uppercase text-gray-500 font-semibold mb-1">Pengeluaran Supplier</p>
            <p id="printTotalPembelian" class="text-xl font-bold text-gray-900"></p>
        </div>
    </div>
    
    <div class="mb-8">
        <h3 class="text-lg font-bold text-gray-800 mb-3 border-l-4 border-indigo-600 pl-3">History Penjualan</h3>
        <table class="w-full">
            <thead>
                <tr>
                    <th>Tanggal & Waktu</th>
                    <th>Kasir</th>
                    <th class="text-right">Total Transaksi</th>
                </tr>
            </thead>
            <tbody id="printPenjualanTableBody">
            </tbody>
        </table>
    </div>

    <div>
        <h3 class="text-lg font-bold text-gray-800 mb-3 border-l-4 border-red-600 pl-3">History Pembelian (Modal)</h3>
        <table class="w-full">
            <thead>
                <tr>
                    <th>Tanggal & Waktu</th>
                    <th>Admin</th>
                    <th>Supplier</th>
                    <th class="text-right">Total Pembelian</th>
                </tr>
            </thead>
            <tbody id="printPembelianTableBody">
            </tbody>
        </table>
    </div>

    <div class="mt-12 pt-8 border-t border-gray-200 text-center text-xs text-gray-400">
        <p>Dokumen ini dihasilkan secara otomatis oleh sistem POS.</p>
    </div>
</div>

<script>
    window.onbeforeprint = () => document.body.classList.add('is-printing');

    window.onafterprint = () => document.body.classList.remove('is-printing');

    const strukPrintCss = `
        @page { size: auto; margin: 0; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            width: 58mm;
            max-width: 100%;
            margin: 0 auto;
            padding: 5mm;
        }
        .struk-header { text-align: center; margin-bottom: 2px; }
        .struk-header h3 { font-size: 9px; font-weight: bold; margin: 0 0 10px; white-space: nowrap; }
        .struk-header p { margin: 0; font-size: 12px; }
        .struk-info p { margin: 2px 0; }
        .struk-divider { text-align: center; margin: 5px 0; letter-spacing: 2px; overflow: hidden; white-space: nowrap; }
        .struk-item { margin-bottom: 5px; }
        .struk-item-name { font-weight: bold; }
        .struk-item-details, .struk-line { display: flex; justify-content: space-between; }
        .struk-summary { margin-top: 5px; font-weight: bold; }
        .text-center { text-align: center; }
    `;

    let strukPrintFrame = null;

    function printStruk(html) {
        if (!strukPrintFrame || !strukPrintFrame.contentDocument) {
            strukPrintFrame = document.createElement('iframe');
            strukPrintFrame.setAttribute('aria-hidden', 'true');
            strukPrintFrame.style.cssText = 'position: fixed; left: -9999px; top: 0; width: 58mm; height: 300mm; border: 0; background: #fff;';
            document.body.appendChild(strukPrintFrame);
        }
        const doc = strukPrintFrame.contentDocument;
        doc.open();
        doc.write('<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Struk</title><style>' + strukPrintCss + '</style></head><body>' + html + '</body></html>');
        doc.close();
        setTimeout(() => {
            strukPrintFrame.contentWindow.focus();
            strukPrintFrame.contentWindow.print();
        }, 100);
    }

    function printReceipt(type) {
        const storeName = document.getElementById('storeNameDisplay').innerText.toUpperCase();
        const storeFont = Math.max(7, Math.min(9, Math.floor(181 / (storeName.length * 0.6))));
        let html = '';
        if (type === 'penjualan') {
            const tanggal = document.getElementById('detailPenjualanTanggal').innerText;
            const kasir = document.getElementById('detailPenjualanKasir').innerText;
            const total = document.getElementById('detailPenjualanTotal').innerText;
            const bayar = document.getElementById('detailPenjualanBayar').innerText;
            const kembalian = document.getElementById('detailPenjualanKembalian').innerText;
            
            const itemsRows = document.querySelectorAll('#detailPenjualanItemsBody tr');
            let itemsHtml = '';
            itemsRows.forEach(row => {
                const cols = row.querySelectorAll('td');
                itemsHtml += `
                <div class="struk-item">
                    <div class="struk-item-name">${cols[0].innerText}</div>
                    <div class="struk-item-details">
                        <span>${cols[1].innerText} x ${cols[2].innerText}</span>
                        <span>${cols[3].innerText}</span>
                    </div>
                </div>`;
            });
            
            html = `
            <div class="struk-header">
                <h3 style="font-size:${storeFont}px">${storeName}</h3><p>Struk Penjualan</p>
            </div>
            <div class="struk-info">
                <p>Tanggal: ${tanggal}</p><p>Kasir: ${kasir}</p>
            </div>
            <div class="struk-divider">--------------------------------</div>
            <div class="struk-items">${itemsHtml}</div>
            <div class="struk-divider">--------------------------------</div>
            <div class="struk-summary">
                <div class="struk-line"><span>Total:</span><span>${total}</span></div>
                <div class="struk-line"><span>Bayar:</span><span>${bayar}</span></div>
                <div class="struk-line"><span>Kembali:</span><span>${kembalian}</span></div>
            </div>
            <div class="struk-divider">================================</div>
            <div class="struk-footer text-center">Terima Kasih</div>
            `;
        } else if (type === 'pembelian') {
            const tanggal = document.getElementById('detailTanggal').innerText;
            const admin = document.getElementById('detailAdmin').innerText;
            const supplier = document.getElementById('detailSupplier').innerText;
            const total = document.getElementById('detailTotal').innerText;
            
            const itemsRows = document.querySelectorAll('#detailItemsBody tr');
            let itemsHtml = '';
            itemsRows.forEach(row => {
                const cols = row.querySelectorAll('td');
                itemsHtml += `
                <div class="struk-item">
                    <div class="struk-item-name">${cols[0].innerText}</div>
                    <div class="struk-item-details">
                        <span>${cols[1].innerText} x ${cols[2].innerText}</span>
                        <span>${cols[3].innerText}</span>
                    </div>
                </div>`;
            });
            
            html = `
            <div class="struk-header">
                <h3 style="font-size:${storeFont}px">${storeName}</h3><p>Bukti Restock/Pembelian</p>
            </div>
            <div class="struk-info">
                <p>Tanggal: ${tanggal}</p>
                <p>Admin: ${admin}</p><p>Supplier: ${supplier}</p>
            </div>
            <div class="struk-divider">--------------------------------</div>
            <div class="struk-items">${itemsHtml}</div>
            <div class="struk-divider">--------------------------------</div>
            <div class="struk-summary">
                <div class="struk-line"><span>Total Modal:</span><span>${total}</span></div>
            </div>
            <div class="struk-divider">================================</div>
            <div class="struk-footer text-center">Disimpan secara otomatis</div>
            `;
        }
        
        printStruk(html);
    }

    let combinedChartInstance = null;
    let currentPeriod = 'harian';
    let lastSalesData = [];
    let lastHistoryPembelian = [];
    let lastHistoryPenjualan = [];

    async function switchPeriod() {
        currentPeriod = document.getElementById('periodSelect').value;
        let title = 'Grafik Penjualan Harian';
        if (currentPeriod === 'bulanan') title = 'Grafik Penjualan Bulanan';
        if (currentPeriod === 'tahunan') title = 'Grafik Penjualan Tahunan';
        
        document.getElementById('chartTitle').innerText = title;
        
        await loadLaporan();
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('filterDate').value = new Date().toLocaleDateString('en-CA');
        loadLaporan();
    });

    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            document.getElementById('filterDate').value = new Date().toLocaleDateString('en-CA');
            loadLaporan();
        }
    });

    async function loadLaporan() {
        try {
            const filterDateVal = document.getElementById('filterDate').value || new Date().toLocaleDateString('en-CA');
            const d = new Date(filterDateVal);
            
            let startDate, endDate, chartApi;
            if (currentPeriod === 'harian') {
                startDate = filterDateVal;
                endDate = filterDateVal;
                chartApi = `/api/laporan/perjam?date=${filterDateVal}`;
            } else if (currentPeriod === 'bulanan') {
                const firstDay = new Date(d.getFullYear(), d.getMonth(), 1);
                const lastDay = new Date(d.getFullYear(), d.getMonth() + 1, 0);
                startDate = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-01`;
                endDate = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(lastDay.getDate()).padStart(2, '0')}`;
                chartApi = `/api/laporan/harian?startDate=${startDate}&endDate=${endDate}`;
            } else {
                startDate = `${d.getFullYear()}-01-01`;
                endDate = `${d.getFullYear()}-12-31`;
                chartApi = `/api/laporan/bulanan?startDate=${startDate}&endDate=${endDate}`;
            }

            const q = `?startDate=${startDate}&endDate=${endDate}`;

            const [resChart, resHistoryPembelian, resHistoryPenjualan, resItems] = await Promise.all([
                fetch(chartApi),
                fetch(`/api/laporan/history-pembelian${q}`),
                fetch(`/api/laporan/history-penjualan${q}`),
                fetch(`/api/laporan/barang${q}`)
            ]);

            const chartData = await resChart.json();
            const historyPembelianData = await resHistoryPembelian.json();
            const historyPenjualanData = await resHistoryPenjualan.json();
            const itemsData = await resItems.json();
            
            lastSalesData = chartData;
            lastHistoryPembelian = historyPembelianData;
            lastHistoryPenjualan = historyPenjualanData;

            updateStats(chartData, itemsData);
            renderCombinedChart(chartData);
            renderHistoryTable(historyPembelianData);
            renderHistoryPenjualanTable(historyPenjualanData);

        } catch (error) {
            console.error('Error loading reports:', error);
        }
    }

    function updateStats(chartData, items) {
        let totalSales = 0;
        let totalHpp = 0;
        let totalPurchases = 0;
        let totalQty = 0;
        
        if (chartData && chartData.length > 0) {
            chartData.forEach(t => {
                totalSales += Number(t.total_penjualan || 0);
                totalHpp += Number(t.total_hpp || 0);
                totalPurchases += Number(t.total_pembelian || 0);
            });
        }
        items.forEach(i => totalQty += Number(i.total_qty));

        let totalProfit = totalSales - totalHpp;

        document.getElementById('statTotalSales').innerText = 'Rp ' + totalSales.toLocaleString('id-ID');
        document.getElementById('statTotalHpp').innerText = 'Rp ' + totalHpp.toLocaleString('id-ID');
        document.getElementById('statTotalProfit').innerText = 'Rp ' + totalProfit.toLocaleString('id-ID');
        document.getElementById('statTotalPembelian').innerText = 'Rp ' + totalPurchases.toLocaleString('id-ID');
    }

    function renderCombinedChart(data) {
        const salesDataPoints = [];
        const volumeDataPoints = [];
        
        const filterDateVal = document.getElementById('filterDate').value || new Date().toLocaleDateString('en-CA');
        const filterD = new Date(filterDateVal);

        if (currentPeriod === 'harian') {
            for (let i = 0; i < 24; i++) {
                const hourStr = String(i).padStart(2, '0');
                const label = `${hourStr}:00`;
                const found = data.find(x => x.jam.includes(` ${hourStr}:00:00`));
                
                salesDataPoints.push({ label, y: found ? Number(found.total_penjualan) : 0 });
                volumeDataPoints.push({ label, y: found ? Number(found.total_transaksi) : 0 });
            }
        } else if (currentPeriod === 'bulanan') {
            const lastDay = new Date(filterD.getFullYear(), filterD.getMonth() + 1, 0).getDate();
            for (let i = 1; i <= lastDay; i++) {
                const dayDate = new Date(filterD.getFullYear(), filterD.getMonth(), i);
                const dateStr = dayDate.toLocaleDateString('en-CA');
                const found = data.find(x => new Date(x.tanggal).toLocaleDateString('en-CA') === dateStr);
                
                const label = `${i}`;
                salesDataPoints.push({ label, y: found ? Number(found.total_penjualan) : 0 });
                volumeDataPoints.push({ label, y: found ? Number(found.total_transaksi) : 0 });
            }
        } else {
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            for (let i = 0; i < 12; i++) {
                const monthStr = `${filterD.getFullYear()}-${String(i + 1).padStart(2, '0')}`;
                const found = data.find(x => x.bulan === monthStr);
                
                salesDataPoints.push({ label: monthNames[i], y: found ? Number(found.total_penjualan) : 0 });
                volumeDataPoints.push({ label: monthNames[i], y: found ? Number(found.total_transaksi) : 0 });
            }
        }

        if (combinedChartInstance) {
            combinedChartInstance.destroy();
        }

        combinedChartInstance = new CanvasJS.Chart("combinedSalesChart", {
            animationEnabled: true,
            theme: "light2",
            exportEnabled: true,
            title: { text: "" },
            toolTip: { shared: true },
            legend: {
                cursor: "pointer",
                itemclick: function (e) {
                    if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                        e.dataSeries.visible = false;
                    } else {
                        e.dataSeries.visible = true;
                    }
                    e.chart.render();
                }
            },
            axisX: {
                labelFontColor: "#9ca3af", labelFontSize: 11, lineThickness: 0, tickThickness: 0
            },
            axisY: {
                title: "Pendapatan", titleFontColor: "#4f46e5", lineColor: "#4f46e5", labelFontColor: "#4f46e5", tickColor: "#4f46e5",
                includeZero: true, prefix: "Rp ", labelFontSize: 11, gridDashType: "dash", gridColor: "#f3f4f6"
            },
            axisY2: {
                title: "Volume Transaksi", titleFontColor: "#10b981", lineColor: "#10b981", labelFontColor: "#10b981", tickColor: "#10b981",
                includeZero: true, labelFontSize: 11, interval: 1
            },
            data: [{
                type: "spline", name: "Total Pendapatan", showInLegend: true, axisYType: "primary", yValueFormatString: "Rp #,##0",
                color: "#4f46e5", markerSize: 8, markerType: "circle", lineThickness: 2, dataPoints: salesDataPoints
            }, {
                type: "spline", name: "Total Transaksi", axisYType: "secondary", showInLegend: true, yValueFormatString: "#,##0 Transaksi",
                color: "#10b981", markerSize: 8, markerType: "square", lineThickness: 2, dataPoints: volumeDataPoints
            }]
        });
        combinedChartInstance.render();
    }

    function renderHistoryTable(data) {
        const tbody = document.getElementById('historyTableBody');
        tbody.innerHTML = '';
        
        if (!data || data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada riwayat pembelian untuk periode ini.</td></tr>`;
            return;
        }
        data.slice(0, 10).forEach(row => {
            const dateStr = new Date(row.created_at).toLocaleString('id-ID', { 
                day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Makassar'
            });
            tbody.innerHTML += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${dateStr}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${row.admin_name || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.nama_supplier || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-red-600">Rp ${Number(row.total_harga).toLocaleString('id-ID')}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <button onclick="viewDetail(${row.id})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md transition-colors hover:bg-indigo-100">Detail</button>
                    </td>
                </tr>
            `;
        });
    }

    async function viewDetail(id) {
        try {
            const response = await fetch(`/api/laporan/history-pembelian/${id}`);
            if (!response.ok) throw new Error('Failed to fetch details');
            const data = await response.json();

            const tx = data.transaksi;
            document.getElementById('detailTanggal').innerText = new Date(tx.created_at).toLocaleString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Makassar'
            });
            document.getElementById('detailAdmin').innerText = tx.admin_name || '-';
            document.getElementById('detailSupplier').innerText = tx.nama_supplier || '-';
            document.getElementById('detailTotal').innerText = 'Rp ' + Number(tx.total_harga).toLocaleString('id-ID');

            const tbody = document.getElementById('detailItemsBody');
            tbody.innerHTML = '';
            data.items.forEach(item => {
                tbody.innerHTML += `
                    <tr class="text-xs">
                        <td class="py-2 pr-2">${item.nama_barang}</td>
                        <td class="py-2 px-2 text-right">${item.qty}</td>
                        <td class="py-2 px-2 text-right">Rp ${Number(item.harga_beli).toLocaleString('id-ID')}</td>
                        <td class="py-2 pl-2 text-right font-medium">Rp ${Number(item.subtotal).toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });

            document.getElementById('receiptModal').classList.remove('hidden');
        } catch (error) {
            console.error('Error fetching detail:', error);
            alert('Gagal memuat detail pembelian.');
        }
    }

    function closeDetailModal() {
        document.getElementById('receiptModal').classList.add('hidden');
    }

    function renderHistoryPenjualanTable(data) {
        const tbody = document.getElementById('historyPenjualanBody');
        tbody.innerHTML = '';
        
        if (!data || data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada riwayat penjualan untuk periode ini.</td></tr>`;
            return;
        }
        data.slice(0, 10).forEach(row => {
            const dateStr = new Date(row.created_at).toLocaleString('id-ID', { 
                day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Makassar'
            });
            tbody.innerHTML += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${dateStr}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${row.kasir_name || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-green-600">Rp ${Number(row.total_harga).toLocaleString('id-ID')}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <button onclick="viewDetailPenjualan(${row.id})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md transition-colors hover:bg-indigo-100">Detail</button>
                    </td>
                </tr>
            `;
        });
    }

    async function viewDetailPenjualan(id) {
        try {
            const response = await fetch(`/api/laporan/history-penjualan/${id}`);
            if (!response.ok) throw new Error('Failed to fetch details');
            const data = await response.json();

            const tx = data.transaksi;
            document.getElementById('detailPenjualanTanggal').innerText = new Date(tx.created_at).toLocaleString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Makassar'
            });
            document.getElementById('detailPenjualanKasir').innerText = tx.kasir_name || '-';
            
            document.getElementById('detailPenjualanTotal').innerText = 'Rp ' + Number(tx.total_harga).toLocaleString('id-ID');
            document.getElementById('detailPenjualanBayar').innerText = 'Rp ' + Number(tx.bayar).toLocaleString('id-ID');
            document.getElementById('detailPenjualanKembalian').innerText = 'Rp ' + Number(tx.kembalian).toLocaleString('id-ID');

            const tbody = document.getElementById('detailPenjualanItemsBody');
            tbody.innerHTML = '';
            data.items.forEach(item => {
                tbody.innerHTML += `
                    <tr class="text-xs">
                        <td class="py-2 pr-2">${item.nama_barang}</td>
                        <td class="py-2 px-2 text-right">${item.qty}</td>
                        <td class="py-2 px-2 text-right">Rp ${Number(item.harga).toLocaleString('id-ID')}</td>
                        <td class="py-2 pl-2 text-right font-medium">Rp ${Number(item.subtotal).toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });

            document.getElementById('receiptPenjualanModal').classList.remove('hidden');
        } catch (error) {
            console.error('Error fetching detail penjualan:', error);
            alert('Gagal memuat detail penjualan.');
        }
    }

    function closeDetailPenjualanModal() {
        document.getElementById('receiptPenjualanModal').classList.add('hidden');
    }

    function printRekapan() {
        const d = new Date(document.getElementById('filterDate').value || new Date());
        let periodLabel = "Harian";
        if (currentPeriod === 'bulanan') periodLabel = "Bulanan";
        else if (currentPeriod === 'tahunan') periodLabel = "Tahunan";

        document.getElementById('printTitle').innerText = `Rekapan Laporan ${periodLabel}`;
        document.getElementById('printDate').innerText = `Dicetak pada: ${new Date().toLocaleString('id-ID')}`;

        document.getElementById('printTotalSales').innerText = document.getElementById('statTotalSales').innerText;
        document.getElementById('printTotalHpp').innerText = document.getElementById('statTotalHpp').innerText;
        document.getElementById('printTotalProfit').innerText = document.getElementById('statTotalProfit').innerText;
        document.getElementById('printTotalPembelian').innerText = document.getElementById('statTotalPembelian').innerText;

        const tbodyPenjualan = document.getElementById('printPenjualanTableBody');
        tbodyPenjualan.innerHTML = '';
        if (!lastHistoryPenjualan || lastHistoryPenjualan.length === 0) {
            tbodyPenjualan.innerHTML = '<tr><td colspan="3" class="text-center py-4">Tidak ada data untuk periode ini.</td></tr>';
        } else {
            lastHistoryPenjualan.forEach(row => {
                const dateStr = new Date(row.created_at).toLocaleString('id-ID', { 
                    day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Makassar'
                });
                tbodyPenjualan.innerHTML += `
                    <tr>
                        <td>${dateStr}</td>
                        <td>${row.kasir_name || '-'}</td>
                        <td class="text-right font-bold text-green-600">Rp ${Number(row.total_harga).toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });
        }

        const tbodyPembelian = document.getElementById('printPembelianTableBody');
        tbodyPembelian.innerHTML = '';
        if (!lastHistoryPembelian || lastHistoryPembelian.length === 0) {
            tbodyPembelian.innerHTML = '<tr><td colspan="4" class="text-center py-4">Tidak ada data untuk periode ini.</td></tr>';
        } else {
            lastHistoryPembelian.forEach(row => {
                const dateStr = new Date(row.created_at).toLocaleString('id-ID', { 
                    day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Makassar'
                });
                tbodyPembelian.innerHTML += `
                    <tr>
                        <td>${dateStr}</td>
                        <td>${row.admin_name || '-'}</td>
                        <td>${row.nama_supplier || '-'}</td>
                        <td class="text-right font-bold text-red-600">Rp ${Number(row.total_harga).toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });
        }
        document.body.classList.add('is-printing');
        window.print();
    }
</script>
@endsection
