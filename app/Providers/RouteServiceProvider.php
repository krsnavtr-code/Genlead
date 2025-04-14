<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;


class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // $this->routes(function () {
        
        //     Route::middleware(['web', 'frontend'])
        //         ->group(base_path('routes/web.php'));

        //     Route::middleware('web')
        //         ->prefix('admin')
        //         ->group(base_path('routes/admin.php'));
            
        // });

        
    }
}
