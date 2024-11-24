<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        // Get last deposit and withdrawal
        $lastDeposit = Transaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->latest()
            ->first();
            
        $lastWithdrawal = Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->latest()
            ->first();

        return response()->json([
            'wallet' => [
                'total_balance' => $wallet->balance,
                'available_balance' => $wallet->available_balance,
                'lien_balance' => $wallet->lien_balance
            ],
            'portfolio_value' => $currentBalance,
            'portfolio_change' => [
                'percentage' => round($percentageChange, 2),
                'direction' => $percentageChange >= 0 ? 'up' : 'down'
            ],
            'last_deposit' => $lastDeposit ? [
                'amount' => $lastDeposit->amount,
                'date' => $lastDeposit->created_at
            ] : null,
            'last_withdrawal' => $lastWithdrawal ? [
                'amount' => $lastWithdrawal->amount,
                'date' => $lastWithdrawal->created_at
            ] : null
        ]);
    }

    public function getTransactionHistory(Request $request)
    {
        $user = $request->user();
        
        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->paginate(10);
            
        return response()->json([
            'transactions' => $transactions->map(function($transaction) {
                return [
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'status' => $transaction->status,
                    'date' => $transaction->created_at,
                    'transaction_hash' => $transaction->transaction_hash
                ];
            }),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'total_pages' => $transactions->lastPage(),
                'total_items' => $transactions->total()
            ]
        ]);
    }
} 