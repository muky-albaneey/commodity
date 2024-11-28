<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Trade;
use App\Models\Wallet;
use App\Models\Market;
use App\Models\Transaction;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetManagementController extends Controller
{
    public function getPortfolioOverview(Request $request)
    {
        $user = $request->user();
        $wallet = Wallet::where('user_id', $user->id)->firstOrFail();
        
        // Get last month's balance for percentage calculation
        $lastMonthBalance = Transaction::where('user_id', $user->id)
            ->where('created_at', '<=', Carbon::now()->subMonth())
            ->sum('amount');
            
        $currentBalance = $wallet->balance;
        $percentageChange = $lastMonthBalance > 0 
            ? (($currentBalance - $lastMonthBalance) / $lastMonthBalance) * 100 
            : 0;


        // bank accounts
        $bankAccounts = BankAccount::where('user_id', $user->id)->with('bank')->get();

        // banks
        $banks = Bank::all();

        // Get transactions grouped by type
        $transactions = [
            'deposits' => Transaction::with('bankAccount.bank')
                ->where('user_id', $user->id)
                ->where('type', 'deposit')
                ->latest()
                ->get()
                ->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'amount' => $transaction->amount,
                        'status' => $transaction->status,
                        'date' => $transaction->created_at,
                        'bank_name' => $transaction->bankAccount->bank->name ?? "",
                        'account_number' => $transaction->bankAccount->account_number ?? "",
                        'transaction_hash' => $transaction->transaction_hash
                    ];
                }),
            'withdrawals' => Transaction::with('bankAccount.bank')
                ->where('user_id', $user->id)
                ->where('type', 'withdrawal')
                ->latest()
                ->get()
                ->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'amount' => $transaction->amount,
                        'status' => $transaction->status,
                        'date' => $transaction->created_at,
                        'bank_name' => $transaction->bankAccount->bank->name ?? "",
                        'account_number' => $transaction->bankAccount->account_number ?? "",
                        'transaction_hash' => $transaction->transaction_hash
                    ];
                })
        ];

        // Get trades grouped by status
        $trades = [
            'pending' => Trade::with('commodity')
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->latest()
                ->get()
                ->map(function ($trade) {
                    return [
                        'id' => $trade->id,
                        'type' => $trade->trade_type,
                        'commodity' => $trade->commodity->name,
                        'quantity' => $trade->quantity,
                        'total_price' => $trade->total_price,
                        'date' => $trade->created_at
                    ];
                }),
            'completed' => Trade::with('commodity')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->latest()
                ->get()
                ->map(function ($trade) {
                    return [
                        'id' => $trade->id,
                        'type' => $trade->trade_type,
                        'commodity' => $trade->commodity->name,
                        'quantity' => $trade->quantity,
                        'total_price' => $trade->total_price,
                        'date' => $trade->created_at
                    ];
                }),
            'failed' => Trade::with('commodity')
                ->where('user_id', $user->id)
                ->where('status', 'cancelled')
                ->latest()
                ->get()
                ->map(function ($trade) {
                    return [
                        'id' => $trade->id,
                        'type' => $trade->trade_type,
                        'commodity' => $trade->commodity->name,
                        'quantity' => $trade->quantity,
                        'total_price' => $trade->total_price,
                        'date' => $trade->created_at
                    ];
                }),
            'cancelled' => Trade::with('commodity')
                ->where('user_id', $user->id)
                ->where('status', 'cancelled')
                ->latest()
                ->get()
                ->map(function ($trade) {
                    return [
                        'id' => $trade->id,
                        'type' => $trade->trade_type,
                        'commodity' => $trade->commodity->name,
                        'quantity' => $trade->quantity,
                        'total_price' => $trade->total_price,
                        'date' => $trade->created_at
                    ];
                })
        ];

       return response()->json([
            'bank_accounts' => $bankAccounts,
            'banks' => $banks,
            'wallet' => [
                'total_balance' => $wallet->balance,
                'available_balance' => $wallet->available_balance,
                'lien_balance' => $wallet->lien_balance,
                'portfolio_value' => $this->calculatePortfolioValue($user->id),
                'user_name' => "{$user->firstName} {$user->lastName}",
                'virtual_account_number' => substr(str_shuffle('0123456789'), 0, 10),
                'change' => [
                    'percentage' => round($percentageChange, 2),
                    'direction' => $percentageChange > 0 ? 'up' : ($percentageChange < 0 ? 'down' : 'neutral')
                ]
            ],
            'transactions' => $transactions,
            'trades' => $trades,
            'last_deposit' => $transactions['deposits']->first()["amount"] ?? 0,
            'last_withdrawal' => $transactions['withdrawals']->first()["amount"] ?? 0
        ]);
    }

    private function calculatePortfolioValue($userId)
    {
        // Get all completed buy trades grouped by commodity
        $holdings = Trade::where('user_id', $userId)
            ->where('status', 'completed')
            ->where('trade_type', 'buy')
            ->select('commodity_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('commodity_id')
            ->get();

        $portfolioValue = 0;

        foreach ($holdings as $holding) {
            // Get the current market price for each commodity
            $currentMarketPrice = Market::where('commodity_id', $holding->commodity_id)
                ->value('market_price');

            if ($currentMarketPrice) {
                $portfolioValue += ($currentMarketPrice * $holding->total_quantity);
            }
        }

        return $portfolioValue;
    }
} 