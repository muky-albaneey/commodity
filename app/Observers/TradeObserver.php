<?php

namespace App\Observers;

use App\Models\Trade;
use App\Models\Market;
use Carbon\Carbon;

class TradeObserver
{
    public function created(Trade $trade)
    {
        $this->updateMarketData($trade);
    }

    private function updateMarketData(Trade $trade)
    {
        $market = Market::firstOrCreate(
            ['commodity_id' => $trade->commodity_id],
            [
                'best_sell' => 0,
                'best_buy' => 0,
                'market_price' => 0,
                'change_24h' => 0,
                'volume_24h' => 0,
                'buyers_count' => 0,
                'sellers_count' => 0,
                'market_value' => 0
            ]
        );

        // Update market price (latest trade price)
        $market->market_price = $trade->total_price / $trade->quantity;

        // Update best buy/sell prices
        if ($trade->trade_type === 'buy') {
            $market->best_buy = $this->calculateBestPrice($trade->commodity_id, 'buy');
            $market->buyers_count = $this->getActiveTraders($trade->commodity_id, 'buy');
        } else {
            $market->best_sell = $this->calculateBestPrice($trade->commodity_id, 'sell');
            $market->sellers_count = $this->getActiveTraders($trade->commodity_id, 'sell');
        }

        // Update 24h volume
        $market->volume_24h = $this->calculate24hVolume($trade->commodity_id);

        // Calculate market value
        $market->market_value = $market->market_price * 
            Trade::where('commodity_id', $trade->commodity_id)->sum('quantity');

        // Calculate 24h change
        $market->change_24h = $this->calculate24hChange($trade->commodity_id);

        $market->save();
    }

    private function calculateBestPrice(string $commodityId, string $type): float
    {
        $trade = Trade::where('commodity_id', $commodityId)
            ->where('trade_type', $type)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->orderBy($type === 'buy' ? 'total_price' : 'total_price', $type === 'buy' ? 'desc' : 'asc')
            ->first();

        return $trade ? ($trade->total_price / $trade->quantity) : 0;
    }

    private function getActiveTraders(string $commodityId, string $type): int
    {
        return Trade::where('commodity_id', $commodityId)
            ->where('trade_type', $type)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->distinct('user_id')
            ->count();
    }

    private function calculate24hVolume(string $commodityId): float
    {
        return Trade::where('commodity_id', $commodityId)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->sum('quantity');
    }

    private function calculate24hChange(string $commodityId): float
    {
        $currentPrice = Trade::where('commodity_id', $commodityId)
            ->latest()
            ->value('total_price');

        $previousPrice = Trade::where('commodity_id', $commodityId)
            ->where('created_at', '<=', Carbon::now()->subDay())
            ->latest()
            ->value('total_price');

        if (!$previousPrice) return 0;

        return (($currentPrice - $previousPrice) / $previousPrice) * 100;
    }
} 