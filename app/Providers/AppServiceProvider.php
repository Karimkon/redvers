<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(WalletService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (file_exists(base_path('routes/admin.php'))) {
        Route::middleware('web')
            ->group(base_path('routes/admin.php'));
    }
    }
}
