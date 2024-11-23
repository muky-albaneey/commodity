<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\Trade;
use App\Models\Commodity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index()
    {
        $markets = Market::with('commodity')->get()->map(function($market) {
            return [
                'name' => $market->commodity->name,
                'symbol' => $market->commodity->symbol,
                'best_sell' => 'N' . number_format($market->best_sell, 2),
                'best_buy' => 'N' . number_format($market->best_buy, 2),
                'market_price' => 'N' . number_format($market->market_price, 2),
                'change_24h' => [
                    'value' => $market->change_24h,
                    'formatted' => $market->change_24h . '%',
                    'trend' => $market->change_24h >= 0 ? 'up' : 'down'
                ],
                'volume' => [
                    'buyers' => $market->buyers_count,
                    'sellers' => $market->sellers_count,
                    'total_kg' => $market->buyers_count + $market->sellers_count
                ],
                'market_value' => 'N' . number_format($market->market_value, 2),
            ];
        });

        $stats = [
            'total_volume' => Trade::sum('quantity') . ' MT',
            'total_securities' => Market::count(),
            'total_participants' => Trade::distinct('user_id')->count(),
            'listed_contracts' => Commodity::count()
        ];

        return response()->json([
            'stats' => $stats,
            'markets' => $markets
        ]);
    }
} 