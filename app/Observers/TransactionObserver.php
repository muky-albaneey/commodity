<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Wallet;

class TransactionObserver
{
    public function updated(Transaction $transaction)
    {
        if ($transaction->isDirty('status') && $transaction->isCompleted()) {
            $wallet = Wallet::where('user_id', $transaction->user_id)->first();
            
            if ($transaction->isDeposit()) {
                $wallet->balance += $transaction->amount;
                $wallet->available_balance += $transaction->amount;
            } else if ($transaction->isWithdrawal()) {
                $wallet->balance -= $transaction->amount;
                $wallet->releaseLien($transaction->amount);
            }
            
            $wallet->save();
        }

        if ($transaction->isDirty('status') && $transaction->isFailed()) {
            $wallet = Wallet::where('user_id', $transaction->user_id)->first();
            
            if ($transaction->isWithdrawal()) {
                $wallet->releaseLien($transaction->amount);
            }
            
            $wallet->save();
        }
    }

    public function created(Transaction $transaction)
    {
        if ($transaction->isCompleted()) {
            $wallet = Wallet::where('user_id', $transaction->user_id)->first();
            
            if ($transaction->isDeposit()) {
                $wallet->balance += $transaction->amount;
                $wallet->available_balance += $transaction->amount;
            } else if ($transaction->isWithdrawal()) {
                $wallet->balance -= $transaction->amount;
                $wallet->releaseLien($transaction->amount);
            }
            
            $wallet->save();
        }
    }
} 