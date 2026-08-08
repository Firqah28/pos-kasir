<?php

namespace App\Http\Controllers;

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
     * Apakah toko milik user yang login belum/tidak aktif (status != aktif)?
     * Master Admin (tanpa toko) tidak pernah kena blokir.
     */
    protected function storeIsInactive(): bool
    {
        $user = Auth::user();

        if (! $user || ! $user->store_id) {
            return false;
        }

        $store = $user->store;

        return $store && ! $store->isAktif();
    }
}
