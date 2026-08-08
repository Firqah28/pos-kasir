<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * ID cabang toko yang sedang diakses.
     * - Admin/Kasir: cabang milik akun.
     * - Master Admin: cabang yang sedang "dilihat" (preview) via session.
     */
    protected function currentStoreId(): ?int
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        if ($user->isMasterAdmin()) {
            return session('preview_store_id') ?: null;
        }

        return $user->store_id;
    }

    /**
     * Cabang toko milik user yang login (bukan master admin).
     */
    protected function currentStore(): ?Store
    {
        $user = Auth::user();

        if (! $user || ! $user->store_id) {
            return null;
        }

        return $user->store;
    }

    /**
     * Apakah toko milik user yang login belum/tidak aktif (status != aktif)?
     * Master Admin (tanpa toko) tidak pernah kena blokir.
     */
    protected function storeIsInactive(): bool
    {
        $store = $this->currentStore();

        return $store && ! $store->isAktif();
    }

    /**
     * Pesan blokir transaksi sesuai status toko.
     */
    protected function storeBlockMessage(): string
    {
        $store = $this->currentStore();

        if (! $store || $store->isAktif()) {
            return '';
        }

        return $store->status === Store::STATUS_MENUNGGU_PEMBAYARAN
            ? 'Toko menunggu pembayaran fee. Transaksi dibatasi sampai pembayaran dikonfirmasi super admin.'
            : 'Toko sedang dinonaktifkan. Transaksi tidak dapat diproses.';
    }
}
