<?php

namespace App\Providers;

use App\Models\Store;
use App\Models\StoreNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('globalSettings', $this->settingsForCurrentUser());
            $this->shareMasterNotifications($view);
            $this->shareStorePendingPayment($view);
        });
    }

    /**
     * Tanda peringatan untuk admin/kasir cabang yang statusnya menunggu pembayaran.
     */
    private function shareStorePendingPayment($view): void
    {
        $user = auth()->user();

        if (! $user || $user->isMasterAdmin() || ! $user->store_id) {
            $view->with('storePendingPayment', false);

            return;
        }

        try {
            $store = $user->store;
            $view->with('storePendingPayment', $store && $store->status === Store::STATUS_MENUNGGU_PEMBAYARAN);
        } catch (\Throwable $e) {
            $view->with('storePendingPayment', false);
        }
    }

    /**
     * Sinkronisasi dan berbagi notifikasi ke seluruh view untuk superadmin.
     */
    private function shareMasterNotifications($view): void
    {
        $user = auth()->user();

        if (! $user || ! $user->isMasterAdmin()) {
            return;
        }

        try {
            StoreNotification::syncFeeDueNotifications();

            $notifications = StoreNotification::query()
                ->whereNull('read_at')
                ->orderByDesc('created_at')
                ->get();

            $view->with('masterNotifications', $notifications);
            $view->with('masterUnreadCount', $notifications->count());
        } catch (\Throwable $e) {
            // Abaikan saat migrasi berjalan atau tabel belum tersedia.
        }
    }

    private function settingsForCurrentUser()
    {
        try {
            $global = DB::table('settings')->whereNull('store_id')->pluck('value', 'key');

            $user = auth()->user();
            if (! $user) {
                return $global;
            }

            $storeId = $user->store_id;
            if ($user->isMasterAdmin()) {
                $storeId = session('preview_store_id');
            }

            if ($storeId) {
                $storeSettings = DB::table('settings')
                    ->where('store_id', $storeId)
                    ->pluck('value', 'key');

                return $global->merge($storeSettings);
            }

            return $global;
        } catch (\Exception $e) {
            // Ignore during migrations or when table doesn't exist
            return collect([]);
        }
    }
}
