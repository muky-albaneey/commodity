<?php

namespace App\Providers;

use App\Models\Trade;
use App\Observers\TradeObserver;
use App\Models\Transaction;
use App\Observers\TransactionObserver;
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
        Transaction::observe(TransactionObserver::class);
    }
}
