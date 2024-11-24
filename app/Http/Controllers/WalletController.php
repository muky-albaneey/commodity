<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
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

    // Fund the wallet
    public function fund(Request $request, $userId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        // Since every user should have a wallet, use firstOrFail.
        $wallet = Wallet::where('user_id', $userId)->firstOrFail();

        // Update the wallet balance
        $wallet->balance += $request->amount;
        $wallet->save();

        return response()->json([
            'message' => 'Wallet funded successfully',
            'balance' => $wallet->balance,
        ], 200);
    }

    // Show wallet balance
    public function show($userId)
    {
        $wallet = Wallet::where('user_id', $userId)->firstOrFail();

        return response()->json([
            'balance' => $wallet->balance,
        ], 200);
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'bank_account_id' => 'required|exists:bank_accounts,id'
        ]);

        $user = $request->user();
        $wallet = Wallet::where('user_id', $user->id)->firstOrFail();

        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $request->amount,
            'status' => 'pending',
            'bank_account_id' => $request->bank_account_id,
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
            'bank_account_id' => 'required|exists:bank_accounts,id'
        ]);

        $user = $request->user();
        $wallet = Wallet::where('user_id', $user->id)->firstOrFail();

        if ($wallet->available_balance < $request->amount) {
            return response()->json([
                'message' => 'Insufficient funds'
            ], 400);
        }

        // Create withdrawal transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'status' => 'pending',
            'bank_account_id' => $request->bank_account_id,
            'transaction_hash' => substr(md5(uniqid()), 0, 12)
        ]);

        return response()->json([
            'message' => 'Withdrawal initiated successfully',
            'transaction' => $transaction
        ]);
    }
}
