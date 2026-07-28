<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Web Views
Route::middleware(['auth'])->group(function () {
    // Both Admin and Kasir can access Dashboard and Kasir
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::get('/transaksi/{id}/cetak', [KasirController::class, 'cetakStruk'])->name('transaksi.cetak');

    // Only Admin can access Master Data and Reports UI
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
        Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        
        // Profile / Store Settings
        Route::get('/profil', [ProfileController::class, 'index'])->name('profil.index');
        Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');
        Route::delete('/profil/logo', [ProfileController::class, 'removeLogo'])->name('profil.removeLogo');
    });
});

// Protected API Endpoints for Frontend Data
Route::prefix('api')->middleware(['auth'])->group(function () {
    // Barang (Read open for Kasir lookup)
    Route::get('/barang', [BarangController::class, 'apiIndex']);
    Route::get('/barang/{id_or_barcode}', [BarangController::class, 'apiShow']);
    
    // Kategori & Supplier (Read open for Kasir filters/lookup)
    Route::get('/kategori', [KategoriController::class, 'apiIndex']);
    Route::get('/supplier', [SupplierController::class, 'apiIndex']);

    // Transaksi (Kasir capability to save sales)
    Route::post('/transaksi', [KasirController::class, 'apiStoreTransaction']);

    // Admin Only APIs (Write/Modify)
    Route::middleware(['role:admin'])->group(function () {
        // Barang Modifications
        Route::post('/barang', [BarangController::class, 'apiStore']);
        Route::put('/barang/{id}', [BarangController::class, 'apiUpdate']);
        Route::delete('/barang/{id}', [BarangController::class, 'apiDestroy']);

        // Kategori Modifications
        Route::post('/kategori', [KategoriController::class, 'apiStore']);
        Route::put('/kategori/{id}', [KategoriController::class, 'apiUpdate']);
        Route::delete('/kategori/{id}', [KategoriController::class, 'apiDestroy']);

        // Supplier Modifications
        Route::post('/supplier', [SupplierController::class, 'apiStore']);
        Route::put('/supplier/{id}', [SupplierController::class, 'apiUpdate']);
        Route::delete('/supplier/{id}', [SupplierController::class, 'apiDestroy']);

        // Pembelian
        Route::post('/pembelian', [PembelianController::class, 'apiStorePembelian']);
    });

    // Laporan (Readonly queries)
    // Technically Admin-only page, but dashboard for Kasir also plots stats, so these endpoints are openly readable to Auth but the view `/laporan` is locked.
    Route::get('/laporan/harian', [LaporanController::class, 'apiHarian']);
    Route::get('/laporan/bulanan', [LaporanController::class, 'apiBulanan']);
    Route::get('/laporan/tahunan', [LaporanController::class, 'apiTahunan']);
    Route::get('/laporan/perjam', [LaporanController::class, 'apiPerjam']);
    Route::get('/laporan/history-penjualan', [LaporanController::class, 'apiHistoryPenjualan']);
    Route::get('/laporan/history-penjualan/{id}', [LaporanController::class, 'apiHistoryPenjualanDetail']);
    Route::get('/laporan/history-pembelian', [LaporanController::class, 'apiHistoryPembelian']);
    Route::get('/laporan/history-pembelian/{id}', [LaporanController::class, 'apiHistoryPembelianDetail']);
    Route::get('/laporan/barang', [LaporanController::class, 'apiLaporanBarang']);
});
