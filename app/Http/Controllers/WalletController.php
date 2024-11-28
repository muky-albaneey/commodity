<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{

    public function index()
    {
        $wallets = Wallet::all();

        return response()->json([
            'wallets' => $wallets,
        ], 200);
    }

    // Show wallet balance
    public function show($userId)
    {
        $wallet = Wallet::where('user_id', $userId)->firstOrFail();

        return response()->json([
            'balance' => $wallet->balance,
            'available_balance' => $wallet->available_balance,
            'lien_balance' => $wallet->lien_balance
        ], 200);
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100'
        ]);

        $user = $request->user();
        $wallet = Wallet::where('user_id', $user->id)->firstOrFail();

        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $request->amount,
            'status' => 'completed', // Note: This is a temporary status for demo purposes
            'bank_account_id' => $request->bank_account_id ?? null,
            'transaction_hash' => substr(md5(uniqid()), 0, 12)
        ]);

        return response()->json([
            'message' => 'Deposit initiated successfully',
            'transaction' => $transaction
        ]);
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'bank_account' => 'required|exists:bank_accounts,id'
        ]);

        $user = $request->user();
        $wallet = Wallet::where('user_id', $user->id)->firstOrFail();

        if ($wallet->available_balance < $request->amount) {
            return response()->json([
                'message' => 'Insufficient available balance'
            ], 400);
        }

        // Place lien on the funds first
        if (!$wallet->placeLien($request->amount)) {
            return response()->json([
                'message' => 'Unable to place hold on funds'
            ], 400);
        }

        // Create withdrawal transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'status' => 'completed', // Note: This is a temporary status for demo purposes
            'bank_account_id' => $request->bank_account,
            'transaction_hash' => Transaction::generateTransactionHash()
        ]);

        return response()->json([
            'message' => 'Withdrawal initiated successfully',
            'transaction' => $transaction
        ]);
    }
}
