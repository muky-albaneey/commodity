<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\Trade;
use App\Models\Commodity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketController extends Controller
{
    public function index(Request $request)
    {
        // Get summary statistics
        $stats = [
            'total_volume' => Trade::sum('quantity'),
            'total_markets' => Market::count(),
            'total_participants' => Trade::distinct('user_id')->count(),
            'listed_commodities' => Commodity::count()
        ];

        // Get market data with relationships
        $markets = Market::with('commodity')
            ->get()
            ->map(function ($market) {
                // Get buyers and sellers counts for last 24 hours
                $buyersSellersData = $this->getBuyersSellersData($market->commodity_id);

                return [
                    'symbol' => $market->commodity->symbol,
                    'name' => $market->commodity->name,
                    'description' => $market->commodity->description,
                    'image' => $market->commodity->image,
                    'best_sell' => $market->best_sell,
                    'best_buy' => $market->best_buy,
                    'market_price' => $market->market_price,
                    'change_24h' => [
                        'value' => $market->change_24h,
                        'direction' => $market->change_24h > 0 ? 'up' : ($market->change_24h < 0 ? 'down' : 'neutral')
                    ],
                    'buyers_sellers' => [
                        'buyers' => $buyersSellersData['buyers_volume'],
                        'sellers' => $buyersSellersData['sellers_volume']
                    ],
                    'market_value' => $market->market_value,
                    'has_chart' => true
                ];
            });

        return response()->json([
            'stats' => $stats,
            'markets' => $markets
        ]);
    }

    private function getBuyersSellersData($commodityId)
    {
        $last24Hours = Carbon::now()->subHours(24);

        $buyersVolume = Trade::where('commodity_id', $commodityId)
            ->where('trade_type', 'buy')
            ->where('created_at', '>=', $last24Hours)
            ->sum('quantity');

        $sellersVolume = Trade::where('commodity_id', $commodityId)
            ->where('trade_type', 'sell')
            ->where('created_at', '>=', $last24Hours)
            ->sum('quantity');

        return [
            'buyers_volume' => $buyersVolume,
            'sellers_volume' => $sellersVolume
        ];
    }
} 