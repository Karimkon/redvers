<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Services\WalletService;

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
        // ✅ Rebind public_path to use public_html
        $customPublicPath = base_path('public_html');
        if (is_dir($customPublicPath)) {
            $this->app->bind('path.public', function () use ($customPublicPath) {
                return $customPublicPath;
            });
        }

        // ✅ Keep your existing route logic
        if (file_exists(base_path('routes/admin.php'))) {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        }
    }
}
