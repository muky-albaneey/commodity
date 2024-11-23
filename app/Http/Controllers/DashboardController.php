<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\User;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{    public function getStats(Request $request)
    {
        // Get authenticated user
        $user = $request->user();
        
        // Get total trading volume
        $tradingVolume = Trade::sum('total_price');
        
        // Get active traders (users who made trades in last 30 days)
        $activeTraders = User::whereHas('trades', function($query) {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        })->count();

        // Calculate active traders trend (compared to previous 30 days)
        $previousActiveTraders = User::whereHas('trades', function($query) {
            $query->whereBetween('created_at', [
                Carbon::now()->subDays(60),
                Carbon::now()->subDays(30)
            ]);
        })->count();

        $tradersTrend = $previousActiveTraders > 0 
            ? round((($activeTraders - $previousActiveTraders) / $previousActiveTraders) * 100, 2)
            : 0;

        // Trading Volume Statistics
        $currentPeriodStart = Carbon::now()->startOfMonth();
        $previousPeriodStart = Carbon::now()->subMonth()->startOfMonth();
         
        $tradingVolume = Trade::sum('total_price');
        $tradesCount = Trade::count();
        $avgTradeSize = $tradesCount > 0 ? $tradingVolume / $tradesCount : 0;
         
        $currentPeriodVolume = Trade::where('created_at', '>=', $currentPeriodStart)->sum('total_price');
        $previousPeriodVolume = Trade::whereBetween('created_at', [
             $previousPeriodStart,
             $currentPeriodStart
        ])->sum('total_price');

        $volumeTrend = $this->calculateTrend($currentPeriodVolume, $previousPeriodVolume);

        // Active Traders Statistics
        $activeTraders = User::whereHas('trades', function($query) {
            $query->where('created_at', '>=', Carbon::now()->subMonths(3));
        })->count();
        
        $newTraders = User::where('created_at', '>=', $currentPeriodStart)->count();
        
        $currentPeriodTraders = User::whereHas('trades', function($query) use ($currentPeriodStart) {
            $query->where('created_at', '>=', $currentPeriodStart);
        })->count();
        
        $previousPeriodTraders = User::whereHas('trades', function($query) use ($previousPeriodStart, $currentPeriodStart) {
            $query->whereBetween('created_at', [$previousPeriodStart, $currentPeriodStart]);
        })->count();
        
        $tradersTrend = $this->calculateTrend($currentPeriodTraders, $previousPeriodTraders);

        // Market Performance Statistics
        $gainersLosers = $this->calculateGainersLosers();

        // Get latest news
        $latestNews = News::latest('date')
            ->take(5)
            ->get();

        // Check user's setup status
        $hasCompletedKYC = !empty($user->kyc_verified_at);
        $hasSetPin = !empty($user->pin);
        $hasBankAccount = $user->bankAccounts()->exists();
        $has2FA = !empty($user->two_factor_secret);

        return response()->json([
            'user' => [
                'customerID' => $user->customerID,
                'customerNam' => "{$user->firstName} {$user->lastName}",
                'setup_status' => [
                    'kyc_completed' => $hasCompletedKYC,
                    'pin_set' => $hasSetPin,
                    'bank_account_added' => $hasBankAccount,
                    'two_factor_enabled' => $has2FA,
                    'setup_completed' => $hasCompletedKYC && $hasSetPin && $hasBankAccount && $has2FA
                ]
            ],
            'trading_volume' => [
                'total' => $tradingVolume,
                'formatted' => 'N' . number_format($tradingVolume, 2),
                'trades_count' => $tradesCount,
                'avg_size' => round($avgTradeSize, 2),
                'trend' => $volumeTrend['direction'],
                'trend_percentage' => $volumeTrend['percentage']
            ],
            'active_traders' => [
                'total' => $activeTraders,
                'new_traders' => $newTraders,
                'active_count' => $currentPeriodTraders,
                'trend' => $tradersTrend['direction'],
                'trend_percentage' => $tradersTrend['percentage']
            ],
            'market_performance' => [
                'volume' => $currentPeriodVolume,
                'gainers' => $gainersLosers['gainers'],
                'losers' => $gainersLosers['losers'],
                'trend' => $gainersLosers['trend']['direction'],
                'trend_percentage' => $gainersLosers['trend']['percentage']
            ],
            'latest_news' => $latestNews
        ]);
    }

    private function calculateGainersLosers()
    {
        $currentPeriodStart = Carbon::now()->startOfDay();
        $previousPeriodStart = Carbon::now()->subDay()->startOfDay();

        // Calculate average price per commodity (total_price / quantity)
        $currentPrices = Trade::where('created_at', '>=', $currentPeriodStart)
            ->groupBy('commodity_id')
            ->select('commodity_id', 
                DB::raw('SUM(total_price) / SUM(quantity) as avg_price'))
            ->get();

        $previousPrices = Trade::whereBetween('created_at', [$previousPeriodStart, $currentPeriodStart])
            ->groupBy('commodity_id')
            ->select('commodity_id', 
                DB::raw('SUM(total_price) / SUM(quantity) as avg_price'))
            ->get();

        $gainers = 0;
        $losers = 0;

        foreach ($currentPrices as $current) {
            $previous = $previousPrices->firstWhere('commodity_id', $current->commodity_id);
            if ($previous) {
                if ($current->avg_price > $previous->avg_price) {
                    $gainers++;
                } elseif ($current->avg_price < $previous->avg_price) {
                    $losers++;
                }
            }
        }

        $trend = $this->calculateTrend($gainers, $losers);

        return [
            'gainers' => $gainers,
            'losers' => $losers,
            'trend' => $trend
        ];
    }

    private function calculateTrend($current, $previous)
    {
        if ($previous == 0) {
            return [
                'direction' => 'neutral',
                'percentage' => 0
            ];
        }

        $percentageChange = (($current - $previous) / $previous) * 100;
        
        return [
            'direction' => $percentageChange > 0 ? 'up' : ($percentageChange < 0 ? 'down' : 'neutral'),
            'percentage' => round($percentageChange, 2)
        ];
    }
}