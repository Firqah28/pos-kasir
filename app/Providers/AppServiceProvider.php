<?php

namespace App\Providers;

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
        });
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
