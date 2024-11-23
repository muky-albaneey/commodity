<?php

namespace App\Console\Commands;

use App\Models\Market;
use App\Models\Trade;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateMarketData extends Command
{
    protected $signature = 'market:update';
    protected $description = 'Update market data including 24h changes';

    public function handle()
    {
        $markets = Market::all();

        foreach ($markets as $market) {
            // Get current day's trades
            $todayTrades = Trade::where('commodity_id', $market->commodity_id)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->get();

            // Get previous day's trades
            $yesterdayTrades = Trade::where('commodity_id', $market->commodity_id)
                ->whereBetween('created_at', [
                    Carbon::now()->subDays(2),
                    Carbon::now()->subDay()
                ])
                ->get();

            // Calculate volume
            $market->volume_24h = $todayTrades->sum('quantity');

            // Calculate price change
            $currentPrice = $todayTrades->last()?->total_price ?? 0;
            $previousPrice = $yesterdayTrades->last()?->total_price ?? 0;

            if ($previousPrice > 0 && $currentPrice > 0) {
                $market->change_24h = (($currentPrice - $previousPrice) / $previousPrice) * 100;
            }

            // Update counts
            $market->buyers_count = $todayTrades->where('trade_type', 'buy')
                ->unique('user_id')->count();
            $market->sellers_count = $todayTrades->where('trade_type', 'sell')
                ->unique('user_id')->count();

            $market->save();
        }

        $this->info('Market data updated successfully');
    }
} 