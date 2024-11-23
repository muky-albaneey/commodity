<?php

namespace App\Providers;

use App\Models\Trade;
use App\Observers\TradeObserver;
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Trade::observe(TradeObserver::class);
    }
}
