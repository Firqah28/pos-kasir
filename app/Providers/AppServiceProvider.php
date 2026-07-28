<?php

namespace App\Providers;

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
        try {
            $settings = \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key');
            \Illuminate\Support\Facades\View::share('globalSettings', $settings);
        } catch (\Exception $e) {
            // Ignore during migrations or when table doesn't exist
            \Illuminate\Support\Facades\View::share('globalSettings', collect([]));
        }
    }
}
